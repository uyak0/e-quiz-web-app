<script setup>
  import TopBar from "@/components/TopBar.vue";
  import axios from "axios";
  import {useRoute, useRouter} from "vue-router";
  import {onMounted, ref} from "vue";
  import VueFeather from "vue-feather";

  const route = useRoute()
  const router = useRouter()
  const API = import.meta.env.VITE_LARAVEL_API

  const classroomQuizzes = ref([])
  const classroomDetails = ref([])
  const classroomDesc = ref('')
  const classroomName = ref('')

  const userRole = route.params.userRole 
  
  async function getClassroomData() {
    const res = await axios.get(API + 'classroom/' + route.params.classroomId)
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
  
  onMounted(() => {
    getClassroomData()
    getQuizzes();
  })

</script>

<template>
  <TopBar />

  <div class="w-full justify-center flex">
    <div class="w-3/4 bg-slate-500 h-screen">
      <div name="title and desc" class="w-full px-2 flex flex-row justify-between">
        <div name="classroom details">
          <div name="name" class="flex flex-row">
            <h1 class="text-6xl" v-show="showName">{{ classroomName }}</h1>
            <div v-if="!showName">
              <input type="text" v-model="classroomName" class="text-black flex-grow bg-transparent py-2 px-2 outline-none text-2xl" placeholder="Add a name...">
              <button @click="changeName" class="text-white">
                <vue-feather type="corner-down-left" class="hover:bg-gray-300 rounded-r-md"></vue-feather>
              </button>
            </div>
            <vue-feather v-show="showNameEditBtn" @click="showName = false; showNameEditBtn = false" size="18" type="edit" v-if="userRole === 'teacher'" class="hover:cursor-pointer hover:text-black duration-300 px-3"></vue-feather>
            <vue-feather @click="showName = true; showNameEditBtn = true" size="18" type="x" v-if="userRole === 'teacher' && !showName" class="hover:cursor-pointer hover:text-black duration-300 px-3"></vue-feather>
          </div>

          <div v-if="classroomDetails.description" name="description" class="flex flex-row">
            <h3 class="text-4xl" v-show="showDesc">{{ classroomDesc }}</h3>
            <div v-if="!showDesc">
              <input type="text" v-model="classroomDesc" class="text-black flex-grow bg-transparent py-2 px-2 outline-none" placeholder="Add a description...">
              <button @click="changeDesc" class="text-white">
                <vue-feather type="corner-down-left" class="hover:bg-gray-300 rounded-r-md"></vue-feather>
              </button>
            </div>
            <vue-feather v-show="showDescEditBtn" @click="showDesc = false; showDescEditBtn = false" size="18" type="edit" v-if="userRole === 'teacher'" class="hover:cursor-pointer hover:text-black duration-300 px-3"></vue-feather>
            <vue-feather @click="showDesc = true; showDescEditBtn = true" size="18" type="x" v-if="userRole === 'teacher' && !showDesc" class="hover:cursor-pointer hover:text-black duration-300 px-3"></vue-feather>
          </div>
          <div v-else-if="!classroomDetails.description" class="flex items-center my-4">
            <input type="text" v-model="classroomDesc" class="text-black flex-grow bg-transparent py-2 px-2 outline-none" placeholder="Add a description...">
            <button v-if="classroomDesc" @click="addDesc" class="text-white px-4 py-2 hover:bg-gray-300 rounded-r-lg transition-colors duration-200">↵</button>
          </div>
        </div>
        <span>
          <button class="group rounded-md w-10 hover:bg-red-800 bg-red-500 text-black">
            <vue-feather class="group-hover:text-white" type="trash-2" v-if="userRole === 'teacher'" @click="deleteClassroom"/> 
          </button>
          <button class="bg-blue-400 hover:bg-blue-600 hover:text-black rounded-md text-2xl px-4">
            <RouterLink v-if="userRole === 'teacher'" :to="{name: 'createQuiz'}" class=""> 
              + Create a Quiz 
            </RouterLink>
          </button>
        </span>
      </div>


      <div v-for="(item, index) of classroomQuizzes" v-if="userRole === 'student'" :key="index" class="flex flex-col">
        <!-- <RouterLink :to="{}" class="rounded-md shadow-sm"> Quiz </RouterLink> -->
        <RouterLink :to="{path: route.params.classroomId + '/quiz/' + item.id}" class="border-gray-600 hover:bg-gray-400 ease-in-out duration-500 mx-10 flex flex-col rounded-md shadow-md bg-gray-500 p-2 cursor-pointer">
          <h1 class="text-4xl">{{item.title}}</h1>
          <h3 class="text-2xl">{{item.description}}</h3>
        </RouterLink>
      </div>

      <div v-for="(item, index) of classroomQuizzes" v-if="userRole === 'teacher'" :key="index" class="py-2 flex flex-col">
        <!-- <RouterLink :to="{}" class="rounded-md shadow-sm"> Quiz </RouterLink> -->
        <RouterLink :to="{path: route.params.classroomId + '/quiz/' + item.id}" class="border-gray-600 hover:bg-gray-400 ease-in-out duration-500 mx-10 flex flex-col rounded-md shadow-md bg-gray-500 p-2 cursor-pointer">
          <h1 class="text-4xl">{{item.title}}</h1>
          <h3 class="text-2xl">{{item.description}}</h3>
        </RouterLink>
      </div>
    </div>
  </div>
</template>
