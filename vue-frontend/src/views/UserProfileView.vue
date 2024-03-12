<script setup>
  import TopBar from '@/components/TopBar.vue';
  import UserAvatar from '@/components/UserAvatar.vue';
  import axios from 'axios';
  import { ref, onMounted } from 'vue';
  import { RouterLink, useRoute, useRouter } from 'vue-router';

  const API = import.meta.env.VITE_LARAVEL_API;
  const route = useRoute();
  const router = useRouter();
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

  function logOut() {
    let confirmLogOut = confirm('Are you sure you want to log out?')
    if (confirmLogOut) {
      localStorage.clear()
      router.push({ path: '/' })
      alert('see you next time :(')
    }
  }
  onMounted(() => {
    getUserData();
  })
</script>

<template>
  <TopBar />

  <div class="flex justify-center">
    <div class="h-screen bg-slate-700 w-3/4 mx-auto py-8 justify-center">
      <div name="Info" class="flex flex-rows">
        <UserAvatar class="w-1/4 h-1/4 mx-8 my-8 rounded-full border border-sky-200" /> 
        <span class="flex flex-col place-self-center overflow-hidden">
          <p class="text-6xl font-bold my-4 mx-4">{{ user.name }}</p>
          <p class="text-4xl mx-4">{{ user.email }}</p>
        </span>
      </div>

      <div @click="logOut" class="w-full text-3xl py-5 px-5 border border-x-0 border-cyan-300 hover:bg-sky-300 hover:text-gray-950 cursor-pointer">
        > Logout
      </div>
    </div>
  </div>
</template>
