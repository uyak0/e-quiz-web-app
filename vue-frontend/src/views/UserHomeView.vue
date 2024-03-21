<script setup>
  import TopBar from '@/components/TopBar.vue';
  import Classroom from "@/components/Classroom.vue";
  import axios from "axios";
  import {onMounted, ref, watchEffect} from "vue";
  import {useRoute, createRouter} from "vue-router";
  import Arrow from "@/components/Arrow.vue";

  const API = import.meta.env.VITE_LARAVEL_API
  const route = useRoute()
  const userId = route.params.userId
  const userRole = route.params.userRole

  const classrooms = ref([])
  const joinOrCreateClassroomBtn = ref(true)

  async function getClassrooms() {
    const response = await axios.get(API + 'user/classrooms/' + userId)
    classrooms.value = response.data
    joinOrCreateClassroomBtn.value = (classrooms.value.length === 0) ? true : false
  }

  onMounted(() => {
    getClassrooms();
  })

  watchEffect(() => {
    console.log(joinOrCreateClassroomBtn.value)
    console.log(classrooms.value.length)
  })
</script>

<template>
  <TopBar v-model:enable-button="joinOrCreateClassroomBtn"/>

  <!-- Arrow pointing to "Join Classroom" button -->
  <div v-if="userRole === 'student' && classrooms.length === 0" class="absolute flex flex-rows left-32">
    <Arrow class="w-16"/>
    <p class="pt-11">Click here to join a classroom!</p>
  </div>

  <div v-if="userRole === 'teacher' && classrooms.length === 0" class="absolute flex flex-rows left-32">
    <Arrow class="w-16"/>
    <p class="pt-11">Click here to create a classroom!</p>
  </div>

  <div v-if="classrooms.length === 0" class="flex justify-center h-screen">
    <div class="wrapper flex flex-col justify-center">
      <p class="text-2xl text-center">No classrooms found!</p>
    </div>
  </div>

  <div v-else class="flex justify-center">
    <div class="sm:w-3/4 w-full flex flex-col sm:flex-row sm:flex-wrap content-start">
      <!-- List of Classroom Boxes -->
      <div v-for="(item, index) of classrooms" :key="index" class="h-fit">
        <Classroom v-model="classrooms[index]" /> 
      </div>

      <!-- Join Classroom (for classrooms no.>= 1)-->
      <div v-if="classrooms.length && userRole === 'student'" :to="{ name: 'joinClassroom' }" class="hover:border-gray-100 hover:text-gray-100 ease-in-out duration-500 my-4 mx-4 rounded-md border-dashed border-2 border-gray-500 bg-transparent text-gray-500 h-48 sm:w-48 overflow-hidden w-ful">
        <div class="w-full h-full text-2xl flex flex-col justify-items-center justify-center">
          <p class="place-self-center font-bold">+</p>
          <p class="place-self-center font-bold text-center">Join More Classrooms</p>
        </div>
      </div>

      <!-- Create Classroom -->
      <RouterLink v-else-if="classrooms.length && userRole === 'teacher'" :to="{ name: 'createClassroom' }" class="hover:border-gray-100 hover:text-gray-100 ease-in-out duration-500 my-4 mx-4 rounded-md border-dashed border-2 border-gray-500 bg-transparent text-gray-500 h-48 sm:w-48 overflow-hidden w-ful">
        <div class="w-full h-full text-2xl flex flex-col justify-items-center justify-center">
          <p class="place-self-center font-bold">+</p>
          <p class="place-self-center font-bold text-center">Create More Classrooms</p>
        </div>
      </RouterLink>
    </div>
  </div>
</template>
