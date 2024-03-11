<script setup>
  import TopBar from '@/components/TopBar.vue';
  import Classroom from "@/components/Classroom.vue";
  import axios from "axios";
  import {onMounted, ref} from "vue";
  import {useRoute, createRouter} from "vue-router";
  import Arrow from "@/components/Arrow.vue";

  const API = import.meta.env.VITE_LARAVEL_API
  const route = useRoute()
  const userId = route.params.id

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
  <TopBar v-model="classrooms"/>

  <!-- Arrow pointing to "Join Classroom" button -->
  <div v-if="classrooms.length === 0" class="absolute flex flex-rows left-32">
    <Arrow class="w-16"/>
    <p class="pt-11">Click here to join a classroom!</p>
  </div>

  <div v-if="classrooms.length === 0" class="flex justify-center h-screen">
    <div class="wrapper flex flex-col justify-center">
      <p class="text-2xl text-center">No classrooms found!</p>
    </div>
  </div>

  <!-- List of Classroom Boxes -->
  <div v-else class="w-3/4 flex flex-rows flex-wrap h-full justify-between">
    <div v-for="(item, index) of classrooms" :key="index" class="flex">
      <Classroom v-model="classrooms[index]"/> 
    </div>
  </div>
</template>
