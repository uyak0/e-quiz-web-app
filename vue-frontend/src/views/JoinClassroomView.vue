<script setup>
  import TopBar from '@/components/TopBar.vue'; 
  import axios from 'axios';
  import { ref } from 'vue';
  import {useRoute} from 'vue-router';

  const API = import.meta.env.VITE_LARAVEL_API
  let classroomId = ''
  const route = useRoute()
  const userId = route.params.id
  let classroomCode = ref('')

  async function joinClassroom() {
    await axios.get(API + 'classroom/' + classroomCode.value)
      .then(response => {
        console.log(response.data)
        classroomId = response.data.id 
      })
      .catch(error => {
        console.log(error)
      })

    await axios.put(API + 'student/' + userId + '/classroom-join/' + classroomId)
      .then(response => {
        console.log(response.data)
      })
      .catch(error => {
        console.log(error)
      })
  }
</script>
<template>
  <TopBar />
  <div class="justify-center flex py-20 my-20">
    <div class="bg-gray-600 rounded-md w-3/4 text-2xl flex flex-col justify-center">
      <p>Enter classroom code</p>
      <input type="text" placeholder="Classroom Code" v-model="classroomCode" class="text-black" />
      <button @click="joinClassroom" class="w-1/4 rounded-md bg-blue-400 hover:bg-blue-600 justify-self-end"> Join </button>
    </div>
  </div>
</template>
