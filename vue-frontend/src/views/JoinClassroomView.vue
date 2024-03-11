<script setup>
  import TopBar from '@/components/TopBar.vue'; 
  import axios from 'axios';
  import { ref } from 'vue';
  import {useRoute, useRouter} from 'vue-router';

  const API = import.meta.env.VITE_LARAVEL_API
  const route = useRoute()
  const router = useRouter()
  const userId = route.params.id
  let classroomCode = ref('')

  function joinClassroom() {
    axios.put(API + 'student/' + userId + '/classroom-join/' + classroomCode.value)
      .then(response => {
        console.log(response.data)
        if (response.data.status === 'success') {
          let classroomId = response.data.classroom_id
          router.push({ path: '/classroom/' + classroomId }) 
        }
        else if (response.data.status === 'already joined') {
          alert(response.data.message)
        }
        else if (response.data.status === 'classroom not found') {
          alert(response.data.message) 
        }
      })
      .catch(error => {
        console.log(error)
      })
  }
</script>
<template>
  <TopBar />
  <div class="justify-center h-full flex py-20 my-20">
    <div class="bg-gray-600 rounded-md w-3/4 text-2xl flex flex-col">
      <div class="flex flex-col justify-around my-4 mx-4">
        <p>Enter classroom code</p>
        <input type="text" placeholder="Classroom Code" v-model="classroomCode" class="rounded-md text-black w-3/4" />
        <button @click="joinClassroom" class="w-1/4 rounded-md bg-blue-400 hover:bg-blue-600 justify-self-end float-right"> Join </button>
      </div>
    </div>
  </div>
</template>
