<script setup>
import TopBar from "@/components/TopBar.vue";
import axios from "axios";
import { useRoute, useRouter } from "vue-router";
import { onMounted, ref, watchEffect } from "vue";
import VueFeather from "vue-feather";
import VueDatePicker from '@vuepic/vue-datepicker'
import '@vuepic/vue-datepicker/dist/main.css'
import Modal from '@/components/Modal.vue'
import { DateTime } from 'luxon';

const route = useRoute()
const router = useRouter()
const API = import.meta.env.VITE_LARAVEL_API

const classroomQuizzes = ref([])
const classroomAssignments = ref([])
const classroomPosts = ref([])

const upcomingQuizzes = ref([])
const classroomDetails = ref([])
const classroomDesc = ref('')
const classroomName = ref('')
const topStudents = ref([])
const copied = ref(false)
const classroomUsers = ref([]);

const createTaskModal = ref(false)
const shareCodeModal = ref(false)
const changeDueModal = ref(false)
const memberListModal = ref(false)

const newDueDate = ref(new Date())

const userRole = route.params.userRole

async function getClassroomData() {
  const [classroomRes, topStudentsRes, classroomDataRes] = await Promise.all([
    axios.get(API + 'classroom/' + route.params.classroomId),
    axios.get(API + 'classroom/top-students/' + route.params.classroomId),
    axios.get(API + 'classroom/data/' + route.params.classroomId)
  ]);

  topStudents.value = topStudentsRes.data;
  classroomDetails.value = {
    ...classroomRes.data,
    memberCount: classroomDataRes.data.memberCount,
    maxMembers: classroomDataRes.data.maxMembers
  };
  classroomName.value = classroomRes.data.name;
  classroomDesc.value = classroomRes.data.description;
}

function getUpcoming() {
  for (let i = 0; i < classroomQuizzes.value.length; i++) {
    if (DateTime.now() < Date.parse(classroomQuizzes.value[i].due_date)) {
      upcomingQuizzes.value.push({
        id: classroomQuizzes.value[i].id,
        title: classroomQuizzes.value[i].title,
        due: classroomQuizzes.value[i].due_date,
        fromNow: dateDiff(classroomQuizzes.value[i].due_date)
      })
    }
  }
  console.log(upcomingQuizzes.value)
}

async function getPosts() {     // Classified assignments, quizzes as Posts
  const quizzes = await axios.get(API + 'classroom/quizzes/' + route.params.classroomId)
  const assignments = await axios.get(API + 'classroom/assignments/' + route.params.classroomId) 
  classroomQuizzes.value = quizzes.data;
  classroomQuizzes.value = classroomQuizzes.value.map(quiz => {
    quiz.type = 'quiz'
    return quiz
  })

  classroomAssignments.value = assignments.data;
  classroomAssignments.value = classroomAssignments.value.map(assignment => {
    assignment.type = 'assignment'
    return assignment
  })

  classroomPosts.value = [...classroomQuizzes.value, ...classroomAssignments.value]
  console.log(classroomPosts.value)
  getUpcoming();
}


async function deleteClassroom() {
  let confirmDlt = confirm('Are you sure you want to delete this classroom? \nThis action cannot be undone. All quizzes and students will be removed from this classroom as well!')
  let confirmDlt2;

  if (confirmDlt) {
    confirmDlt2 = prompt("Type the classroom's name to proceed: ")
  }
  else return

  if (confirmDlt2 !== classroomDetails.value.name) {
    return alert('Classroom name does not match, aborting...')
  }

  const res = await axios.delete(API + 'classroom/' + route.params.classroomId)
  if (res.data.status === 'success') {
    alert('Classroom deleted successfully, redirecting...')
    router.push('/' + route.params.userRole + '/' + route.params.userId + '/home')
  }
}

const showName = ref(true)
const showDesc = ref(true)
const showNameEditBtn = ref(true)
const showDescEditBtn = ref(true)

async function changeName() {
  let confirmChange = confirm('Are you sure you want to change the classroom name?')
  if (!confirmChange) return

  await axios.put(API + 'classroom/update-name', {
    classroom_id: route.params.classroomId,
    name: classroomName.value
  })
  showName.value = true
  showNameEditBtn.value = true
}

async function changeDesc() {
  let confirmChange = confirm('Are you sure you want to change the classroom description?')
  if (!confirmChange) return

  await axios.put(API + 'classroom/update-desc', {
    classroom_id: route.params.classroomId,
    description: classroomDesc.value
  })
  showDesc.value = true
  showDescEditBtn.value = true
}

async function openMemberListModal() {
  try {
    const response = await axios.get(API + 'classroom/users/' + route.params.classroomId);
    classroomUsers.value = response.data;
  } catch (error) {
    console.error("Failed to fetch classroom users", error);
  }
  memberListModal.value = true;
}


function copyCode() {
  const code = classroomDetails.value.code
  navigator.clipboard.writeText(code)
  copied.value = true
  setTimeout(() => {
    copied.value = false
  }, 2000)
}

function dateDiff(date) {
  let now = DateTime.now()
  date = Date.parse(date)
  let due = DateTime.fromMillis(date)
  let diff = due.toRelativeCalendar(now, { style: 'long' })
  diff = diff.charAt(0).toUpperCase() + diff.slice(1)
  return diff
}

function changeDue(date) {
  changeDueModal.value = true
  newDueDate.value = Date.parse(date)
}

onMounted(() => {
  getClassroomData();
  getPosts();
})
</script>

<template>
  <div class="bg-soft-white dark:bg-soft-black">
    <TopBar />

    <div class="flex justify-center">
      <div class="w-full md:w-3/4 2xl:w-1/2 place-items-center justify-center flex flex-col">
        <div name="title, desc and teacher btns"
          class="md:rounded-md w-full justify-self-center bg-gray-200 dark:bg-slate-700 md:m-4 px-4 pb-4 pt-1 flex lg:flex-row flex-col justify-between">
          <div name="classroom details" class="text-black dark:text-darkMode">

            <div name="name" class="flex flex-row py-3 overflow-hidden truncate">
              <h1 class="text-4xl lg:text-6xl truncate" v-show="showName">{{ classroomName }}</h1>
              <div v-if="!showName" class="flex flex-row">
                <input type="text" :value="classroomName" size="15"
                  class="text-4xl lg:text-6xl text-black flex-grow bg-transparent outline-none"
                  placeholder="Add a name...">
                <button @click="changeName" class="text-white">
                  <vue-feather type="corner-down-left" class="hover:bg-gray-300 rounded-r-md"></vue-feather>
                </button>
              </div>
              <vue-feather v-show="showNameEditBtn" @click="showName = false; showNameEditBtn = false" size="18"
                type="edit" v-if="userRole === 'teacher'"
                class="hover:cursor-pointer hover:text-black duration-300 px-3"></vue-feather>
              <vue-feather @click="showName = true; showNameEditBtn = true" size="18" type="x"
                v-if="userRole === 'teacher' && !showName"
                class="hover:cursor-pointer dark:hover:text-black hover:text-gray-500 duration-300 px-3"></vue-feather>
            </div>

            <div v-if="classroomDetails.description" name="description" class="flex flex-row">
              <h3 class="md:text-2xl text-lg" v-show="showDesc">{{ classroomDesc }}</h3>
              <div v-if="!showDesc">
                <input type="text" :value="classroomDesc"
                  class="text-black md:text-2xl text-lg flex-grow bg-transparent py-2 outline-none"
                  placeholder="Add a description...">
                <button @click="changeDesc" class="text-white">
                  <vue-feather type="corner-down-left" class="hover:bg-gray-300 rounded-r-md"></vue-feather>
                </button>
              </div>
              <vue-feather v-show="showDescEditBtn" @click="showDesc = false; showDescEditBtn = false" size="18"
                type="edit" v-if="userRole === 'teacher'"
                class="hover:cursor-pointer hover:text-black duration-300 px-3"></vue-feather>
              <vue-feather @click="showDesc = true; showDescEditBtn = true" size="18" type="x"
                v-if="userRole === 'teacher' && !showDesc"
                class="hover:cursor-pointer hover:text-black duration-300 px-3"></vue-feather>
            </div>



            <div v-else-if="!classroomDetails.description" class="flex items-center my-4">
              <input type="text" v-model="classroomDesc"
                class="text-black text-4xl flex-grow bg-transparent py-2 outline-none"
                placeholder="Add a description...">
              <button @click="changeDesc" class="text-white">
                <vue-feather type="corner-down-left" class="hover:bg-gray-300 rounded-r-md"></vue-feather>
              </button>
              <vue-feather @click="showDesc = true; showDescEditBtn = true" size="18" type="x"
                v-if="userRole === 'teacher' && !showDesc"
                class="hover:cursor-pointer hover:text-black duration-300 px-3"></vue-feather>
            </div>
            <div name="classroom details" class="text-black dark:text-darkMode flex items-center">
              <p class="text-xl">Members: {{ classroomDetails.memberCount }} / {{ classroomDetails.maxMembers }}</p>
              <div class="ml-2 mt-2">
                <button @click="openMemberListModal" class="hover:cursor-pointer hover:text-black duration-300">
                  <vue-feather type="info" size="18"></vue-feather>
                </button>
              </div>
            </div>
          </div>


          <!-- Teacher buttons -->
          <span name="teacher btns" v-if="userRole === 'teacher'"
            class="md:flex-nowrap sm:flex-wrap py-2 gap-2 flex h-fit lg:flex-col">
            <button class="flex group rounded-md hover:bg-red-800 bg-red-500 text-black w-fit h-fit self-end">
              <vue-feather class="p-1 place-self-center group-hover:text-white" type="trash-2"
                @click="deleteClassroom" />
            </button>
            <button name="big button" @click="createTaskModal = true"
              class="after:content-['+_Task'] md:after:content-['+_Create_a_Task'] text-center bg-blue-400 hover:bg-blue-600 hover:text-black rounded-md text-md md:text-lg px-4">
            </button>
            <button @click="shareCodeModal = true"
              class="after:content-['+_Student'] md:after:content-['+_Add_a_Student'] text-center text-md md:text-lg bg-gray-300 rounded-md px-2 text-gray-900">
            </button>
          </span>
        </div>


        <div name="content" class="w-full place-items-center md:place-items-start h-screen flex flex-col md:flex-row">
          <!-- SideBar -->
          <div name="sidebar" class="md:w-fit w-full flex flex-col">
            <!-- LEADERBOARD -->
            <div v-if="topStudents.length" class="p-1 w-full md:w-auto px-2">
              <h1 class="text-lg text-gray-900 dark:text-darkMode">Classroom Leaderboard</h1>
              <div class="flex flex-col w-full">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                  <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                    <div class="shadow overflow-hidden border-b border-gray-200 dark:border-gray-700 rounded-lg">
                      <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                        <thead class="dark:bg-gray-700 bg-gray-50 *:text-gray-500 *:dark:text-darkMode">
                          <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                              Name
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                              Points
                            </th>
                          </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-700 divide-y dark:divide-gray-600 divide-gray-200">
                          <tr v-for="(student, index) in topStudents" :key="index"
                            class="duration-300 ease-in-out *:hover:bg-gray-300 *:dark:hover:bg-gray-800">
                            <td class="px-6 py-4 whitespace-nowrap flex-row flex gap-2">
                              <div class="text-sm text-gray-900 dark:text-darkMode">{{ student.name }}</div>
                              <vue-feather v-if="index == 0" type="star" size="20" fill="yellow" stroke="black" />
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                              <div class="text-sm text-gray-900 dark:text-darkMode">{{ student.points }}</div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Upcoming Quizzes -->
            <div v-if="upcomingQuizzes.length" class="p-1 w-full md:w-auto px-2 text-gray-900 dark:text-darkMode">
              <h1 class="text-lg text-gray-900 dark:text-darkMode">Upcoming</h1>
              <div class="border dark:border-gray-600 shadow-md bg-white dark:bg-gray-600 rounded-md p-2">
                <div v-for="(quiz, index) of upcomingQuizzes" :key="index">
                  <RouterLink :to="{ name: 'quiz', params: { quizId: quiz.id } }"
                    class="pb-4 flex flex-col w-full group">
                    <h1 class="text-2xl group-hover:underline">{{ quiz.title }}</h1>
                    <h3 class="text-md">Due: {{ quiz.due }}</h3>
                    <h4 class="text-sm">{{ quiz.fromNow }}</h4>
                  </RouterLink>
                </div>
              </div>
            </div>
          </div>

          <div class="flex flex-col w-full">
            <div v-for="(post, index) of classroomPosts" :key="index" class="py-2 flex flex-col w-full">
              <!-- Quizzes -->
              <div v-if="post.type === 'quiz'">
                <RouterLink :to="{ name: 'quiz', params: { quizId: post.id } }"
                  class="hover:bg-gray-400 ease-in-out duration-200 md:ml-5 mx-2 flex flex-col rounded-md shadow-md bg-soft-white text-gray-900 dark:text-darkMode border-gray-200 dark:bg-gray-500 dark:border-none dark:hover:text-gray-800 p-2 cursor-pointer group">
                  <h1 class="border-b-2 dark:border-gray-400 font-jetBrains dark:group-hover:border-gray-300">Quiz</h1>
                  <div class="w-full flex flex-row justify-between place-items-center">
                    <h1 class="text-4xl pb-2">{{ post.title }}</h1>
                    <h3 v-show="!changeDueModal" class="text-md hover:underline"
                      @click.stop.prevent="changeDue(post.due_date)">Due: {{ post.due_date }}</h3>
                    <span v-show="changeDueModal" class="flex gap-1">
                      <input type="date" v-model="newDueDate" v-show="changeDueModal"
                        class="text-md hover:underline dark:bg-gray-600 dark:text-darkMode" @click.stop.prevent>
                      <input type="time" v-show="changeDueModal" :value="Date.parse(post.due_date)" @click.stop.prevent>
                    </span>
                  </div>
                </RouterLink>
              </div>

              <!-- Assignments -->
              <div v-if="post.type === 'assignment'">
                <RouterLink :to="{ name: 'assignment', params: { assignmentId: post.id } }"
                  class="hover:bg-gray-400 ease-in-out duration-200 md:ml-5 mx-2 flex flex-col rounded-md shadow-md bg-soft-white text-gray-900 dark:text-darkMode border-gray-200 dark:bg-gray-500 dark:border-none dark:hover:text-gray-800 p-2 cursor-pointer group">
                  <h1 class="border-b-2 dark:border-gray-400 font-jetBrains dark:group-hover:border-gray-300">Assignment</h1>
                  <div class="w-full flex flex-row justify-between place-items-center">
                    <h1 class="text-4xl pb-2">{{ post.title }}</h1>
                    <h3 v-show="!changeDueModal" class="text-md hover:underline"
                      @click.stop.prevent="changeDue(post.due_date)">Due: {{ post.due_date }}</h3>
                    <span v-show="changeDueModal" class="flex gap-1">
                      <input type="date" v-model="newDueDate" v-show="changeDueModal"
                        class="text-md hover:underline dark:bg-gray-600 dark:text-darkMode" @click.stop.prevent>
                      <input type="time" v-show="changeDueModal" :value="Date.parse(post.due_date)" @click.stop.prevent>
                    </span>
                  </div>
                </RouterLink>
              </div>
            </div>
          </div>

          
        </div>
      </div>
    </div>

    <!-- MODALS -->
    <Modal v-model="shareCodeModal">
      <div
        class="place-self-center sm:w-full md:w-3/4 lg:w-1/4 text-center rounded-md h-fit bg-gray-600 text-gray-100 p-6">
        <h1>Copy this code and share it to your students!</h1>
        <!-- Copy to Clipboard -->
        <div class="font-jetBrains mb-4 flex place-items-center w-full bg-gray-500 text-gray-900 rounded-md p-2">
          <input type="text" :value="classroomDetails.code" class="text-4xl w-full bg-gray-500 text-gray-900" readonly>
          <vue-feather type="clipboard" class="text-gray-300 cursor-pointer" @click="copyCode"></vue-feather>
        </div>
        <span name="tooltip" v-show="copied"
          class="relative z-50 bg-black after:content-[''] after:absolute after:top-full after:left-1/2 after:border-t-black after:border-x-transparent after:border-b-transparent after:border-4 rounded-md px-2">
          Copied to clipboard!
        </span>
      </div>
    </Modal>

    <Modal v-model="createTaskModal">
      <div
        class="place-self-center sm:w-full md:w-3/4 lg:w-1/4 text-center rounded-md h-fit bg-gray-600 text-gray-100 p-6">
        <div class="w-full flex flex-row justify-end">
          <VueFeather type="x" @click="createTaskModal = false" class="w-fit hover:text-gray-500 cursor-pointer" />
        </div>
        <h1 class="pb-2 text-2xl">What do you want to create?</h1>
        <span class="py-2 gap-2 flex flex-row *:rounded-md *:w-1/2 *:py-5 text-xl text-gray-900">
          <RouterLink :to="{ name: 'createQuiz' }" class="bg-red-400 hover:bg-red-600 duration-300 hover:text-gray-200">
            <vue-feather type="book-open" size="50" />
            <h1>Quiz</h1>
          </RouterLink>
          <RouterLink :to="{ name: 'createAssignment' }"
            class="bg-blue-400 hover:bg-blue-600 duration-300 hover:text-gray-200">
            <vue-feather type="file-text" size="50" />
            <h1>Assignment</h1>
          </RouterLink>
        </span>
      </div>
    </Modal>

    <Modal v-model="memberListModal">
      <div class="p-4 bg-red-300 w-3/4 h-fit place-self-center">
        <h2 class="text-2xl font-bold mb-4 text-black">Member List</h2>
        <ul>
          <li v-for="user in classroomUsers" :key="user.id" class="text-black">{{ user.name }}</li>
        </ul>
      </div>
    </Modal>

  </div>
</template>
