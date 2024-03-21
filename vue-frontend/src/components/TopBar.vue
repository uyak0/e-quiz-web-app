<script setup>
  import ToggleTheme from './ToggleTheme.vue';
  import UserAvatar from './UserAvatar.vue';
  import { useRoute } from 'vue-router';
  import { onMounted, ref, watchEffect } from 'vue';
  import axios from 'axios';

  const API = import.meta.env.VITE_LARAVEL_API

  const route = useRoute()
  const pageName = route.meta.title 
  const userId = route.params.userId
  const userRole = route.params.userRole

  const enableButton = defineModel('enableButton')

  const joinClassroomModal = ref(false) 
  const createClassroomModal = ref(false)

  const classroomCode = ref('')
  const classroomName = ref('');
  const classroomDesc = ref('');

  async function joinClassroom() {
    const join = await axios.put(API + 'student/' + userId + '/classroom-join/' + classroomCode.value)
    console.log(join.data)

    if (join.data.status === 'success') {
      alert('Joined classroom successfully, redirecting to classroom...')
      let classroomId = join.data.classroom_id
      router.push({ path: '/classroom/' + classroomId }) 
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

  onMounted(() => {
    console.log(route.meta)
  })
</script>

<template>
  <div class="sticky font-jetBrains flex flex-rows text-2xl justify-between px-2 py-1 bg-gray-600 place-items-center">
    <div name="left modules" class="">
      <RouterLink :to="{ name: 'userHome' }"> E-Quizz </RouterLink>

      <!--Create/join classroom button-->
      <button @click="joinClassroomModal = !joinClassroomModal" v-if="userRole === 'student' && pageName.includes('Home') && enableButton" class="ml-2 bg-blue-300 hover:bg-blue-600 duration-150 ease-in hover:text-white text-black rounded-md px-2">
        + Join a classroom 
      </button>

      <button @click="createClassroomModal = !createClassroomModal" v-else-if="userRole === 'teacher' && pageName.includes('Home') && enableButton"  to="classroom/create" class="ml-2 bg-blue-300 hover:bg-blue-600 duration-150 ease-in hover:text-white text-black rounded-md px-2"> 
        + Create a classroom
      </button>

      <!-- Join Classroom Modal -->
      <span @click="joinClassroomModal = !joinClassroomModal" :class="{ 'fixed flex justify-center z-50 top-0 left-0 h-full w-full bg-black/50': joinClassroomModal, 'hidden': !joinClassroomModal }">
        <div class="bg-gray-600 rounded-md w-3/4 h-fit text-2xl flex place-self-center">
          <div class="flex flex-col justify-around my-4 mx-4">
            <p>Enter classroom code</p>
            <input type="text" placeholder="Classroom Code" v-model="classroomCode" class="rounded-md text-black w-3/4" />
            <button @click="joinClassroom" class="w-1/4 rounded-md bg-blue-400 hover:bg-blue-600 justify-self-end float-right"> Join </button>
          </div>
        </div>
      </span>
      <!-- ---------- -->

      <!-- Create Classroom Modal -->
      <span @click="createClassroomModal = !createClassroomModal" :class="{ 'fixed flex justify-center z-50 top-0 left-0 h-full w-full bg-black/50': createClassroomModal, 'hidden': !createClassroomModal }">
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
      </span>
      <!-- -------------- -->

    </div>

    <!-- Page Name -->
    <div class="hidden sm:block font-firaSans font-bold">
      {{ pageName }}
    </div>
    
    <div name="theme and user" class="flex flex-row gap-10">
      <div class="place-items-center place-self-center">
        <ToggleTheme @dark-toggle="darkToggle"/> 
      </div>

      <RouterLink :to="{ name: 'userProfile' }">
        <div class="flex justify-center w-10 h-10 overflow-hidden bg-gray-100 rounded-full dark:bg-gray-600">
          <UserAvatar />
        </div>
      </RouterLink>
    </div>

  </div>
</template>
