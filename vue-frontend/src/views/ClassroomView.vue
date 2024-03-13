<script setup>
  import TopBar from "@/components/TopBar.vue";
  import axios from "axios";
  import {useRoute} from "vue-router";
  import {onMounted, ref} from "vue";
  import { getStorageItem } from "@/functions/getStorageItem.js";

  const route = useRoute()
  const API = import.meta.env.VITE_LARAVEL_API

  const userRole = getStorageItem('user_role')
  const createQuizPath = route.params.classroomId + '/quiz/create' // had to manually add classroomId infront of /quiz/create otherwise 
                                                                  // it'd direct to classroom/quiz/create for some reason
  const classroomQuizzes = ref([])
  const classroomDetails = ref([])
  
  async function getClassroom() {
    await axios.get(API + 'classroom/' + route.params.classroomId)
      .then(response => {
        console.log(response.data);
        classroomDetails.value = response.data;
      })
      .catch(error => {
        console.log(error);
      });
  }

  async function getQuizzes() {
    await axios.get(API + 'classroom/quizzes/' + route.params.classroomId)
      .then(response => {
        console.log(response.data);
        classroomQuizzes.value = response.data;
        
      })
      .catch(error => {
        console.log(error);
      });
  }
  
  onMounted(() => {
    getClassroom();
    getQuizzes();
  })

</script>

<template>
  <TopBar />
  <div class="flex flex-rows flex-grow-0 bg-fixed bg-[url('https://via.placeholder.com/200')]">
    <div class="flex flex-col">
      <h1 class="text-9xl">{{classroomDetails.name}}</h1>
      <h3 class="text-4xl">{{classroomDetails.description}}</h3>
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
