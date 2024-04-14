<script setup>
import TopBar from "@/components/TopBar.vue";
import axios from "axios";
import { useRoute, useRouter } from "vue-router";
import { onMounted, ref, watchEffect } from "vue";
import VueFeather from "vue-feather";
import Modal from '@/components/Modal.vue'

const route = useRoute()
const router = useRouter()
const API = import.meta.env.VITE_LARAVEL_API

const classroomQuizzes = ref([])
const classroomDetails = ref([])
const classroomDesc = ref('')
const classroomName = ref('')
const topStudents = ref([])
const copied = ref(false)

const createTaskModal = ref(false)
const shareCodeModal = ref(false)

const userRole = route.params.userRole

async function getClassroomData() {
  const res = await axios.get(API + 'classroom/' + route.params.classroomId)
  const res2 = await axios.get(API + 'classroom/top-students/' + route.params.classroomId)

  topStudents.value = res2.data;
  classroomDetails.value = res.data;
  classroomName.value = res.data.name
  classroomDesc.value = res.data.description
}

async function getQuizzes() {
  const res = await axios.get(API + 'classroom/quizzes/' + route.params.classroomId)
  classroomQuizzes.value = res.data;
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

function copyCode() {
  const code = classroomDetails.value.code
  navigator.clipboard.writeText(code)
  copied.value = true
  setTimeout(() => {
    copied.value = false
  }, 2000)
}

onMounted(() => {
  getClassroomData()
  getQuizzes();
})
</script>

<template>
  <div class="bg-soft-white dark:bg-soft-black">
    <TopBar />

    <div class="w-full place-items-center justify-center flex flex-col">
      <div name="title, desc and teacher btns"
        class="rounded-md w-3/4 bg-gray-200 dark:bg-slate-700 m-4 px-4 pb-4 pt-1 flex lg:flex-row flex-col justify-between">
        <div name="classroom details" class="text-black dark:text-darkMode">

          <div name="name" class="flex flex-row py-3 overflow-hidden truncate">
            <h1 class="text-xl lg:text-6xl truncate" v-show="showName">{{ classroomName }}</h1>
            <div v-if="!showName" class="flex flex-row">
              <input type="text" v-model="classroomName"
                class="text-xl lg:text-6xl text-black flex-grow bg-transparent outline-none"
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
            <h3 class="text-4xl" v-show="showDesc">{{ classroomDesc }}</h3>
            <div v-if="!showDesc">
              <input type="text" v-model="classroomDesc"
                class="text-black text-4xl flex-grow bg-transparent py-2 outline-none"
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
              class="text-black text-4xl flex-grow bg-transparent py-2 outline-none" placeholder="Add a description...">
            <button @click="changeDesc" class="text-white">
              <vue-feather type="corner-down-left" class="hover:bg-gray-300 rounded-r-md"></vue-feather>
            </button>
            <vue-feather @click="showDesc = true; showDescEditBtn = true" size="18" type="x"
              v-if="userRole === 'teacher' && !showDesc"
              class="hover:cursor-pointer hover:text-black duration-300 px-3"></vue-feather>
          </div>
        </div>

        <!-- Teacher buttons -->
        <span name="teacher btns" v-if="userRole === 'teacher'"
          class="md:flex-nowrap sm:flex-wrap py-2 gap-2 flex h-fit">
          <button class="flex group rounded-md hover:bg-red-800 bg-red-500 text-black">
            <vue-feather class="p-2 place-self-center group-hover:text-white" type="trash-2" @click="deleteClassroom" />
          </button>
          <button name="big button" @click="createTaskModal = true"
            class="after:content-['+_Task'] md:after:content-['+_Create_a_Task'] text-center bg-blue-400 hover:bg-blue-600 hover:text-black rounded-md text-md md:text-2xl px-4">
          </button>
          <button @click="shareCodeModal = true"
            class="after:content-['+_Student'] md:after:content-['+_Add_a_Student'] text-center text-md md:text-2xl bg-gray-300 rounded-md px-2 text-gray-900">
          </button>
        </span>
      </div>

      <!-- MODALS -->
      <Modal v-model="shareCodeModal">
        <div class="place-self-center sm:w-full md:w-3/4 lg:w-1/4 text-center rounded-md h-fit bg-gray-600 text-gray-100 p-6">
          <h1>Copy this code and share it to your students!</h1>
          <!-- Copy to Clipboard -->
          <div class="font-jetBrains mb-4 flex place-items-center w-full bg-gray-500 text-gray-900 rounded-md p-2">
            <input type="text" :value="classroomDetails.code" class="text-4xl w-full bg-gray-500 text-gray-900"
              readonly>
            <vue-feather type="clipboard" class="text-gray-300 cursor-pointer" @click="copyCode"></vue-feather>
          </div>
          <span name="tooltip" v-show="copied"
            class="relative z-50 bg-black after:content-[''] after:absolute after:top-full after:left-1/2 after:border-t-black after:border-x-transparent after:border-b-transparent after:border-4 rounded-md px-2">
            Copied to clipboard!
          </span>
        </div>
      </Modal>

      <Modal v-model="createTaskModal">
        <div class="place-self-center sm:w-full md:w-3/4 lg:w-1/4 text-center rounded-md h-fit bg-gray-600 text-gray-100 p-6">
          <div class="w-full flex flex-row justify-end">
            <VueFeather type="x" @click="createTaskModal = false" class="w-fit hover:text-gray-500 cursor-pointer" />
          </div>
          <h1 class="pb-2 text-2xl">What do you want to create?</h1>
          <span class="py-2 gap-2 flex flex-row *:rounded-md *:w-1/2 *:py-5 text-xl text-gray-900">
            <button class="bg-red-400 hover:bg-red-600 duration-300 hover:text-gray-200">
              <RouterLink :to="{ name: 'createQuiz' }">
                <vue-feather type="book-open" size="50" />
                <h1>Quiz</h1>
              </RouterLink>
            </button>
            <button class="bg-blue-400 hover:bg-blue-600 duration-300 hover:text-gray-200">
              <RouterLink :to="{ name: 'createAssignment' }">
                <vue-feather type="file-text" size="50" />
                <h1>Assignment</h1>
              </RouterLink>
            </button>
          </span>
        </div>
      </Modal>

      <div name="content" class="w-3/4 h-screen flex flex-row">

        <!-- LEADERBOARD -->
        <div v-if="topStudents.length" class="p-1">
          <h1 class="text-lg text-gray-900 dark:text-darkMode">Classroom Leaderboard</h1>
          <div class="flex flex-col w-fit">
            <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
              <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                  <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                      <tr>
                        <th scope="col"
                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                          Name
                        </th>
                        <th scope="col"
                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                          Points
                        </th>
                      </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                      <tr v-for="(student, index) in topStudents" :key="index"
                        class="duration-300 ease-in-out *:hover:bg-gray-300">
                        <td class="px-6 py-4 whitespace-nowrap flex-row flex gap-2">
                          <div class="text-sm text-gray-900">{{ student.name }}</div>
                          <vue-feather v-if="index == 0" type="star" size="20" fill="yellow" stroke="black" />
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                          <div class="text-sm text-gray-500">{{ student.points }}</div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div v-for="(item, index) of classroomQuizzes" :key="index" class="py-2 flex flex-col w-full">
          <!-- <RouterLink :to="{}" class="rounded-md shadow-sm"> Quiz </RouterLink> -->
          <RouterLink :to="{ path: route.params.classroomId + '/quiz/' + item.id }"
            class="hover:bg-gray-400 ease-in-out duration-200 ml-5 flex flex-col rounded-md shadow-md bg-soft-white text-gray-900 dark:text-darkMode border-gray-200 dark:bg-gray-500 dark:border-none dark:hover:text-gray-800 p-2 cursor-pointer group">
            <h1 class="border-b-2 dark:border-gray-400 font-jetBrains dark:group-hover:border-gray-300">Quiz</h1>
            <div class="w-full flex flex-row justify-between place-items-center">
              <h1 class="text-4xl pb-2">{{ item.title }}</h1>
              <h3 class="text-md">Due: {{ item.due_date }}</h3>
            </div>
          </RouterLink>
        </div>
      </div>
    </div>
  </div>
</template>
