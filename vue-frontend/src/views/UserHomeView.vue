<script setup>
  import TopBar from '@/components/TopBar.vue';
  import Classroom from "@/components/Classroom.vue";
  import Arrow from "@/components/Arrow.vue";
  import Modal from "@/components/Modal.vue";
  import axios from "axios";
  import {onMounted, ref, watchEffect, nextTick} from "vue";
  import {useRoute, useRouter } from "vue-router";

  const API = import.meta.env.VITE_LARAVEL_API
  const route = useRoute()
  const router = useRouter()
  const userId = route.params.userId
  const userRole = route.params.userRole

  const classrooms = ref([])
  const classroomCode = ref('')
  const classroomName = ref('')
  const classroomDesc = ref('')

  const joinOrCreateClassroomBtn = ref(true)
  const modalEnabled = ref(false)
  const modalInput = ref(null)

  async function getClassrooms() {
    const response = await axios.get(API + 'user/classrooms/' + userId)
    classrooms.value = response.data
    joinOrCreateClassroomBtn.value = (classrooms.value.length === 0) ? true : false
  }

  async function joinClassroom() {
    const join = await axios.put(API + 'student/' + userId + '/classroom-join/' + classroomCode.value)
    console.log(join.data)

    if (join.data.status === 'success') {
      alert('Joined classroom successfully, redirecting to classroom...')
      let classroomId = join.data.classroom_id
      router.push({ name: 'classroom', params: { classroomId: classroomId }}) 
    }
    else if (join.data.status === 'already joined') {
      alert(join.data.message)
    }
    else if (join.data.status === 'classroom not found') {
      alert(join.data.message) 
    }
  }

  async function createClassroom() {
    const classroomData = {
      classroom_name: classroomName.value,
      classroom_desc: classroomDesc.value,
    }
    try {
      const res = await axios.post(API + 'classroom/create', classroomData)
      if (res.data.status === 'success') {
        alert('Classroom created successfully, redirecting...')
        router.push(res.data.classroom_id) 
      }
    } catch (err) {
      console.log(err)
    }
  }

  function enableModal() { 
    modalEnabled.value = true 
    nextTick(() => {
      modalInput.value.focus()
    })
  }

  onMounted(() => {
    getClassrooms();
  })
</script>

<template>
  <TopBar @modal-enabled="enableModal" v-model:enable-button="joinOrCreateClassroomBtn"/>

  <!-- Arrow pointing to "Join Classroom" button -->
  <div v-if="userRole === 'student' && classrooms.length === 0" class="absolute flex flex-rows left-32">
    <Arrow class="w-16"/>
    <p class="pt-11">Click here to join a classroom!</p>
  </div>

  <div v-if="userRole === 'teacher' && classrooms.length === 0" class="absolute flex flex-rows left-32">
    <Arrow class="w-16"/>
    <p class="pt-11">Click here to create a classroom!</p>
  </div>
  <!-- ----- -->

  <div v-if="classrooms.length === 0" class="flex justify-center h-screen">
    <div class="wrapper flex flex-col justify-center">
      <p class="text-2xl text-center">No classrooms found!</p>
    </div>
  </div>

  <!-- List of Classroom Boxes -->
  <div v-else class="flex justify-center">
    <div class="sm:w-3/4 w-full flex flex-col sm:flex-row sm:flex-wrap content-start">
      <div v-for="(item, index) of classrooms" :key="index" class="h-fit">
        <Classroom v-model="classrooms[index]" /> 
      </div>

      <!-- Join Classroom (for classrooms no.>= 1)-->
      <button @click="enableModal" v-if="classrooms.length && userRole === 'student'" class="cursor-pointer hover:border-gray-100 hover:text-gray-100 ease-in-out duration-500 my-4 mx-4 rounded-md border-dashed border-2 border-gray-500 bg-transparent text-gray-500 h-48 sm:w-48 overflow-hidden w-full">
        <div class="w-full h-full text-2xl flex flex-col justify-items-center justify-center">
          <p class="place-self-center font-bold">+</p>
          <p class="place-self-center font-bold text-center">Join More Classrooms</p>
        </div>
      </button>

      <!-- Create Classroom -->
      <button @click="enableModal" v-else-if="classrooms.length && userRole === 'teacher'" class="hover:border-gray-100 hover:text-gray-100 ease-in-out duration-500 my-4 mx-4 rounded-md border-dashed border-2 border-gray-500 bg-transparent text-gray-500 h-48 sm:w-48 overflow-hidden w-full">
        <div class="w-full h-full text-2xl flex flex-col justify-items-center justify-center">
          <p class="place-self-center font-bold">+</p>
          <p class="place-self-center font-bold text-center">Create More Classrooms</p>
        </div>
      </button>
    </div>
  </div>
  <!-- --------- -->

  <!-- Modals -->
  <Modal v-if="userRole==='student'" v-model="modalEnabled">
    <form @submit.prevent class="bg-gray-600 rounded-md w-3/4 h-fit text-2xl flex place-self-center z-20">
      <div class="flex flex-col justify-around my-4 mx-4">
        <p>Enter classroom code</p>
        <input ref="modalInput" v-model="classroomCode" type="text" placeholder="Classroom Code" class="rounded-md text-black w-3/4" />
        <button @click="joinClassroom" class="w-1/4 rounded-md bg-blue-400 hover:bg-blue-600 justify-self-end float-right"> Join </button>
      </div>
    </form>
  </Modal>

  <Modal v-else v-model="modalEnabled">
    <form @submit.prevent class="bg-gray-600 rounded-md w-3/4 h-fit flex place-self-center">
      <div class="flex flex-col text-black">
        <input ref="modalInput" type="text" v-model="classroomName" placeholder="Classroom Name" class="border-2 border-gray-300 rounded-md p-2 m-2">
        <input ref="modalInput" type="text" v-model="classroomDesc" placeholder="Description" class="border-2 border-gray-300 rounded-md p-2 m-2">
      </div>

      <div class="float-right flex flex-row mx1">
        <button @click="createClassroom" class="bg-blue-400 rounded-md px-2 mx-2">Create Classroom</button>
        <button>Cancel</button>
      </div>
    </form>
  </Modal>
  <!-- -----  -->

</template>
