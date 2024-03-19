<script setup>
  import TopBar from '@/components/TopBar.vue';
  import { ref } from 'vue';
  import axios from 'axios';

  const API = import.meta.env.VITE_LARAVEL_API
  const classroomName = ref('');
  const classroomDesc = ref('');

  async function createClassroom() {
    const classroomData = {
      classroom_name: classroomName.value,
      classroom_desc: classroomDesc.value,
    }
    try {
      const res = await axios.post(API + 'classroom/create', classroomData)
    } catch (err) {
      console.log(err)
    }
  }
  
</script>
<template>
  <TopBar />
  <div>
    <div class="flex flex-col text-black">
      <input type="text" v-model="classroomName" placeholder="Classroom Name" class="border-2 border-gray-300 rounded-md p-2 m-2">
      <input type="text" v-model="classroomDesc" placeholder="Description" class="border-2 border-gray-300 rounded-md p-2 m-2">
    </div>


    <div class="float-right flex flex-row mx-2">
      <button @click="createClassroom" class="bg-blue-400 rounded-md px-2 mx-2">Create Classroom</button>
      <button>Cancel</button>
    </div>
  </div>
</template>
