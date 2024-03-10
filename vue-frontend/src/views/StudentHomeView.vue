<script setup>
  import TopBar from '@/components/TopBar.vue';
  import Classroom from "@/components/Classroom.vue";
  import axios from "axios";
  import {onMounted, ref} from "vue";
  import {useRoute, createRouter} from "vue-router";

  const API = import.meta.env.VITE_LARAVEL_API
  const route = useRoute()
  const userId = route.params.id

  const pageName = 'Home'
  const classrooms = ref([])

  async function getClassrooms() {
    await axios.get(API + 'student/classrooms/' + userId)
      .then(response => {
        console.log(response.data)
        classrooms.value = response.data
      })
      .catch(error => {
        console.log(error)
      })
  }
  onMounted(() => {
    getClassrooms();
  })
</script>

<template>
  <TopBar v-model="pageName"/>
  <div v-if="classrooms.length === 0" class="flex justify-center h-screen">
    <div class="wrapper flex flex-col justify-center">
      <p class="text-2xl text-center">No classrooms found!</p>
      <span class="text-xl bg-blue-300 text-black rounded-md my-5 py-4 text-center cursor-pointer hover:bg-blue-600 hover:text-white">
        <RouterLink to="classroom/join">
          Join a classroom
        </RouterLink>
      </span>
    </div>
  </div>

  <div v-else class="w-1/4 flex flex-rows justify-between">
    <div v-for="(item, index) of classrooms" :key="index">
      <Classroom v-model="classrooms[index]"/> 
    </div>
  </div>
</template>
