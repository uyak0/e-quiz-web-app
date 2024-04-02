<script setup>
  import TopBar from "@/components/TopBar.vue";
  import DeleteIcon from "@/components/icons/DeleteIcon.vue";
  import axios from "axios";
  import {useRoute, useRouter} from "vue-router";
  import {onMounted, ref} from "vue";
  import feather from 'feather-icons'; 

  const route = useRoute()
  const router = useRouter()
  const API = import.meta.env.VITE_LARAVEL_API

  const classroomQuizzes = ref([])
  const classroomDetails = ref([])
  const classroomDesc = ref('')
  const userRole = route.params.userRole 
  
  async function getClassroomData() {
    const res = await axios.get(API + 'classroom/' + route.params.classroomId)
    classroomDetails.value = res.data;
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

    if (confirmDlt2 !== classroomDetails.value.name) {
      return alert('Classroom name does not match, aborting...')
    }

    const res = await axios.delete(API + 'classroom/' + route.params.classroomId)
    if (res.data.status === 'success') {
      alert('Classroom deleted successfully, redirecting...')
      router.push('/' + route.params.userRole + '/' + route.params.userId + '/home')
    }
  }

  function changeDesc() {
    axios.put(API + 'classroom/update-desc', {
      classroom_id: route.params.classroomId,
      description: classroomDesc.value
    })
    classroomDetails.value.description = classroomDesc.value
    classroomDesc.value = ''
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
        <div>
          <h1 class="text-6xl">{{classroomDetails.name}}</h1>
          <button v-if="userRole === 'teacher'" class="text-xl">
            <vue-feather type="edit"></vue-feather>
          </button>
          <h3 class="text-4xl">{{classroomDetails.description}}</h3>
          <div v-if="!classroomDetails.description" class="flex items-center my-4">
            <input type="text" v-model="classroomDesc" class="text-black flex-grow bg-transparent py-2 px-2 outline-none" placeholder="Add a description...">
            <button v-if="classroomDesc" @click="changeDesc" class="text-white px-4 py-2 hover:bg-gray-300 rounded-r-lg transition-colors duration-200">↵</button>
          </div>
        </div>
        <span>
          <DeleteIcon class="w-10" v-if="userRole === 'teacher'" @click="deleteClassroom"/> 
          <RouterLink v-if="userRole === 'teacher'" :to="{name: 'createQuiz'}" class="bg-blue-400 hover:bg-blue-600 hover:text-black rounded-md text-2xl"> 
            + Create a Quiz 
          </RouterLink>
        </span>
      </div>


    <div v-for="(item, index) of classroomQuizzes" v-if="userRole === 'student'" :key="index" class="flex flex-col">
      <!-- <RouterLink :to="{}" class="rounded-md shadow-sm"> Quiz </RouterLink> -->
      <RouterLink :to="{path: route.params.classroomId + '/quiz/' + item.id}" class="border-gray-600 hover:bg-gray-400 ease-in-out duration-500 mx-10 flex flex-col rounded-md shadow-md bg-gray-500 p-2 cursor-pointer">
        <h1 class="text-4xl">{{item.title}}</h1>
        <h3 class="text-2xl">{{item.description}}</h3>
      </RouterLink>
    </div>
    </div>
  </div>
</template>
