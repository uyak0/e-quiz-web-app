<script setup>
  import TopBar from "@/components/TopBar.vue";
  import DeleteIcon from "@/components/icons/DeleteIcon.vue";
  import axios from "axios";
  import {useRoute} from "vue-router";
  import {onMounted, ref} from "vue";

  const route = useRoute()
  const API = import.meta.env.VITE_LARAVEL_API

  const createQuizPath = route.params.classroomId + '/quiz/create' // had to manually add classroomId infront of /quiz/create otherwise 
                                                                  // it'd direct to classroom/quiz/create for some reason
  const classroomQuizzes = ref([])
  const classroomDetails = ref([])
  const userRole = ref('')
  
  async function getClassroomData() {
    const res = await axios.get(API + 'classroom/' + route.params.classroomId)
    classroomDetails.value = res.data;
  }

  async function getQuizzes() {
    const res = await axios.get(API + 'classroom/quizzes/' + route.params.classroomId)
    classroomQuizzes.value = res.data;
  }

  async function getUserRole() {
    const res = await axios.get(API + 'user/role')
    console.log(res.data)
    userRole.value = res.data
  }

  async function deleteClassroom() {
    let confirmDlt = confirm('Are you sure you want to delete this classroom? \nThis action cannot be undone. All quizzes and students will be deleted as well!')
    if (confirmDlt) {
      let confirmDlt2 = prompt("Type the classroom's name to proceed: ")
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

  onMounted(() => {
    getClassroomData()
    getUserRole()
    console.log(userRole.value)
    getQuizzes();
  })

</script>

<template>
  <TopBar />

  <div class="flex flex-rows flex-grow-0 bg-fixed bg-[url('https://via.placeholder.com/200')]">
    <div class="flex flex-col">
      <h1 class="text-6xl">{{classroomDetails.name}}</h1>
      <h3 class="text-4xl">{{classroomDetails.description}}</h3>
      <DeleteIcon v-if="userRole === 'teacher'" @click="deleteClassroom"/> 
    </div>

    <RouterLink v-if="userRole === 'teacher'" :to="createQuizPath" class="bg-blue-400 hover:bg-blue-600 hover:text-black rounded-md text-2xl px-2"> 
      + Create a Quiz 
    </RouterLink>
    
    <div v-for="(item, index) of classroomQuizzes" v-if="userRole === 'student'" :key="index" class="flex flex-col">
      <!-- <RouterLink :to="{}" class="rounded-md shadow-sm"> Quiz </RouterLink> -->
      <RouterLink :to="{path: route.params.classroomId + '/quiz/' + item.id}" class="flex flex-col rounded-md shadow-md bg-gray-500 p-2 cursor-pointer">
        <h1 class="text-4xl">{{item.title}}</h1>
        <h3 class="text-2xl">{{item.description}}</h3>
      </RouterLink>
    </div>

  </div>
</template>
