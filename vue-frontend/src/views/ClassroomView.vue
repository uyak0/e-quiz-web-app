<script setup>
  import TopBar from "@/components/TopBar.vue";
  import axios from "axios";
  import {useRoute} from "vue-router";
  import {onMounted, ref} from "vue";
  import { getStorageItem } from "@/functions/getStorageItem.js";

  const route = useRoute()
  const API = import.meta.env.VITE_LARAVEL_API
  const classroom = ref({});

  const userRole = getStorageItem('user_role')
  const createQuizPath = route.params.classroomId + '/quiz/create' // had to manually add classroomId infront of /quiz/create otherwise 
                                                                  // it'd direct to classroom/quiz/create for some reason
  
  async function getClassroom() {
    await axios.get(API + 'classroom/' + route.params.classroomId)
      .then(response => {
        console.log(response.data);
        classroom.value = response.data;
      })
      .catch(error => {
        console.log(error);
      });
  }

  async function getQuizzes() {
    await axios.get(API + '')
  }
  
  onMounted(() => {
    getClassroom();
  })

</script>

<template>
  <TopBar />
  <div class="banner">
    <img src="https://via.placeholder.com/200" alt="banner" class="w-full h-96 object-cover">
  </div>
  <div class="flex flex-rows flex-grow-0">
    <div class="flex flex-col">
      <h1 class="text-9xl">{{classroom.name}}</h1>
      <h3 class="text-4xl">{{classroom.description}}</h3>
    </div>
    <RouterLink v-if="userRole === 'teacher'" :to="createQuizPath" class="bg-blue-400 hover:bg-blue-600 hover:text-black rounded-md text-2xl px-2"> 
      + Create a Quiz 
    </RouterLink>
  </div>
</template>
