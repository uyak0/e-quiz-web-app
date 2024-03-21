<script setup>
  import TopBar from '@/components/TopBar.vue';
  import Classroom from "@/components/Classroom.vue";
  import Arrow from "@/components/Arrow.vue";
  import Modal from "@/components/Modal.vue";
  import axios from "axios";
  import {onMounted, ref, watchEffect} from "vue";
  import {useRoute, createRouter} from "vue-router";

  const API = import.meta.env.VITE_LARAVEL_API
  const route = useRoute()
  const userId = route.params.userId
  const userRole = route.params.userRole

  const classrooms = ref([])
  const joinOrCreateClassroomBtn = ref(true)
  const modalEnabled = ref(false) 

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
      <button @click="modalEnabled = !modalEnabled" v-if="classrooms.length && userRole === 'student'" :to="{ name: 'joinClassroom' }" class="cursor-pointer hover:border-gray-100 hover:text-gray-100 ease-in-out duration-500 my-4 mx-4 rounded-md border-dashed border-2 border-gray-500 bg-transparent text-gray-500 h-48 sm:w-48 overflow-hidden w-ful">
        <div class="w-full h-full text-2xl flex flex-col justify-items-center justify-center">
          <p class="place-self-center font-bold">+</p>
          <p class="place-self-center font-bold text-center">Join More Classrooms</p>
        </div>
      </button>

      <!-- Create Classroom -->
      <button @click="modalEnabled = !modalEnabled" v-else-if="classrooms.length && userRole === 'teacher'" :to="{ name: 'createClassroom' }" class="hover:border-gray-100 hover:text-gray-100 ease-in-out duration-500 my-4 mx-4 rounded-md border-dashed border-2 border-gray-500 bg-transparent text-gray-500 h-48 sm:w-48 overflow-hidden w-ful">
        <div class="w-full h-full text-2xl flex flex-col justify-items-center justify-center">
          <p class="place-self-center font-bold">+</p>
          <p class="place-self-center font-bold text-center">Create More Classrooms</p>
        </div>
      </button>

      <!-- Modals -->
      <Modal v-if="userRole === 'student'" v-model="modalEnabled">
        <div class="bg-gray-600 rounded-md w-3/4 h-fit text-2xl flex place-self-center">
          <div class="flex flex-col justify-around my-4 mx-4">
            <p>Enter classroom code</p>
            <input type="text" placeholder="Classroom Code" v-model="classroomCode" class="rounded-md text-black w-3/4" />
            <button @click="joinClassroom" class="w-1/4 rounded-md bg-blue-400 hover:bg-blue-600 justify-self-end float-right"> Join </button>
          </div>
        </div>
      </Modal>

      <Modal v-else v-model="modalEnabled">
        <div class="bg-gray-600 rounded-md w-3/4 h-fit flex place-self-center">
          <div class="flex flex-col text-black">
            <input type="text" v-model="classroomName" placeholder="Classroom Name" class="border-2 border-gray-300 rounded-md p-2 m-2">
            <input type="text" v-model="classroomDesc" placeholder="Description" class="border-2 border-gray-300 rounded-md p-2 m-2">
          </div>

          <div class="float-right flex flex-row mx-2">
            <button @click="createClassroom" class="bg-blue-400 rounded-md px-2 mx-2">Create Classroom</button>
            <button>Cancel</button>
          </div>
        </div>
      </Modal>
      <!-- -----  -->

    </div>
  </div>
</template>
