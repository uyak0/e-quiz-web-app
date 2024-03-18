<script setup>
  import TopBar from '@/components/TopBar.vue';
  import Classroom from "@/components/Classroom.vue";
  import axios from "axios";
  import {onMounted, ref} from "vue";
  import {useRoute, createRouter} from "vue-router";
  import Arrow from "@/components/Arrow.vue";

  const API = import.meta.env.VITE_LARAVEL_API
  const route = useRoute()
  const userId = route.params.userId
  const userRole = route.params.userRole

  const classrooms = ref([])

  async function getClassrooms() {
    const response = await axios.get(API + 'user/classrooms/' + userId)
    classrooms.value = response.data
  }

  onMounted(() => {
    getClassrooms();
  })
</script>

<template>
  <TopBar />

  <div v-if="classrooms.length === 0" class="flex justify-center h-screen">
    <div class="wrapper flex flex-col justify-center">
      <p class="text-2xl text-center">No classrooms found!</p>
    </div>
  </div>

  <!-- List of Classroom Boxes -->
  <div v-else class="sm:w-3/4 w-full flex flex-col sm:flex-row sm:flex-wrap h-full gap-y-px sm:gap-4">
    <div v-for="(item, index) of classrooms" :key="index" class="flex">
      <Classroom v-model="classrooms[index]"/> 
    </div>
    <!-- Join Classroom (for classrooms no.>= 1)-->
    <RouterLink v-if="classrooms.length >= 1" :to="{ name: 'joinClassroom' }" class="hover:border-gray-100 hover:text-gray-100 ease-in-out duration-500 my-4 mx-4 rounded-md border-dashed border-2 border-gray-500 bg-transparent text-gray-500 h-48 sm:w-48 overflow-hidden w-ful">
      <div class="w-full h-full text-2xl flex flex-col justify-items-center justify-center">
        <p class="place-self-center font-bold">+</p>
        <p class="place-self-center font-bold">Join Classroom</p>
      </div>
    </RouterLink>
  </div>
</template>
