<script setup>
  import TopBar from '@/components/TopBar.vue';
  import UserAvatar from '@/components/UserAvatar.vue';
  import axios from 'axios';
  import { ref, onMounted } from 'vue';
  import { RouterLink, useRoute, useRouter } from 'vue-router';
  import { getStorageItem } from '@/functions/getStorageItem.js';

  const API = import.meta.env.VITE_LARAVEL_API;
  const route = useRoute();
  const router = useRouter();
  const user = ref({});
  const userId = route.params.userId
  const userRole = route.params.userRole

  async function getUserData() {
    const userData = await axios.get(API + 'user/?id=' + userId)
    user.value = userData.data 
    console.log(userData.data)
  }

  async function logOut() {
    let confirmLogOut = confirm('Are you sure you want to log out?')

    if (confirmLogOut) {
      const logOutUser = await axios.post(API + 'auth/logout')
      router.push({ path: '/' })
    }
  }
  onMounted(() => {
    getUserData();
  })
</script>

<template>
  <TopBar />

  <div class="flex justify-center">
    <div class="h-screen bg-slate-700 w-full sm:w-3/4 mx-auto py-8 justify-center">
      <div name="Info" class="flex flex-rows">
        <UserAvatar class="w-1/4 h-1/4 mx-8 my-8 rounded-full border border-sky-200" /> 
        <span class="py-2 flex flex-col place-self-center overflow-hidden">
          <p class="text-4xl sm:text-6xl font-bold sm:my-4 mx-4">{{ user.name }}</p>
          <p class="text-2xl sm:text-4xl sm:my-4 mx-4">{{ user.email }}</p>
          <p class="text-xl sm:text-2xl mx-4 px-2 bg-red-500 rounded-md text-red-800" :class="{'bg-green-400 text-green-800': userRole === 'student' }" >{{ userRole }}</p>
        </span>
      </div>

      <div @click="logOut" class="w-full text-3xl py-5 px-5 border border-x-0 border-cyan-300 hover:bg-sky-300 hover:text-gray-950 cursor-pointer">
        > Logout
      </div>
    </div>
  </div>
</template>
