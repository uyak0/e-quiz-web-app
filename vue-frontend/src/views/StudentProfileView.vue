<script setup>
  import TopBar from '@/components/TopBar.vue';
  import UserAvatar from '@/components/UserAvatar.vue';
  import axios from 'axios';
  import { ref, onMounted } from 'vue';

  const API = import.meta.env.VITE_LARAVEL_API;
  const user = ref({});
  const userId = localStorage.getItem('user_id');

  async function getUserData() {
    await axios.get(API + 'user/' + userId)
      .then(response => {
        user.value = response.data 
        console.log(response.data)
      })
      .catch(error => {
        console.log(error)
      })
  }

  onMounted(() => {
    getUserData();
  })
</script>

<template>
  <TopBar />

  <div class="flex justify-center">
    <div class="h-screen bg-slate-700 w-3/4 mx-auto py-8 justify-center">
      <div name="avatar and name" class="flex flex-rows">
        <UserAvatar class="w-1/4 h-1/4 mx-8 my-8 rounded-full border border-sky-200" /> 
        <p class="text-6xl font-bold my-24 mx-4">{{ user.name }}</p>
      </div>

      <div>
        <span></span>
      </div>
    </div>
  </div>
</template>
