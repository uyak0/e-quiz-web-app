<script setup>
  import TopBar from '@/components/TopBar.vue';
  import UserAvatar from '@/components/UserAvatar.vue';
  import NormalIcon from '@/components/icons/NormalIcon.vue';
  import NoDisturbIcon from '@/components/icons/NoDisturbIcon.vue';
  import InvisibleIcon from '@/components/icons/InvisibleIcon.vue';
  import axios from 'axios';
  import { ref, onMounted } from 'vue';
  import { useRoute, useRouter } from 'vue-router';

  const API = import.meta.env.VITE_LARAVEL_API;
  const route = useRoute();
  const router = useRouter();
  const user = ref({});
  const userId = route.params.userId
  const userRole = route.params.userRole
  const studentPoints = ref(0)
  const mode = ref('normal'); //default is normal

  async function getUserData() {
    const userData = await axios.get(API + 'user/?id=' + userId)
    user.value = userData.data 
    mode.value = userData.data.mode; // Set the user's mode
    console.log(userData.data)
  }

  async function getPoints() {
    const res = await axios.get(API + 'student/points', { params: { id: userId } })
    studentPoints.value = res.data
  }

  async function logOut() {
    let confirmLogOut = confirm('Are you sure you want to log out?')

    if (confirmLogOut) {
      const logOutUser = await axios.post(API + 'auth/logout')
      router.push({ path: '/' })
    }
  }
  async function updateMode() {
    
    try {
      await axios.post(`${API}user/mode`, { mode: mode.value });
      alert('Mode updated successfully.');
    } catch (error) {
      console.error('Error updating mode:', error);
      alert('Failed to update mode.');
    }
  }

  
  onMounted(() => {
    getUserData();
    getPoints();
    
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
          <select v-model="mode" @change="updateMode" class="ml-4 inline-block text-black">
            <option value="normal">Normal</option>
            <option value="do not disturb">Do Not Disturb</option>
            <option value="invisible">Invisible</option>
          </select>
          <p class="text-2xl sm:text-4xl sm:my-4 mx-4">{{ user.email }}</p>
          <span class="flex flex-row gap-2 mx-4 font-jetBrains">
            <p class="w-fit text-xl sm:text-2xl px-2 rounded-md text-gray-900" :class="{'bg-green-400': userRole === 'student', 'bg-red-500': userRole === 'teacher'}" >{{ userRole }}</p>
            <p v-if="userRole === 'student'" class="w-fit text-xl sm:text-2xl px-2 bg-pink-300 rounded-md text-gray-900">{{ studentPoints }}pts</p>
          </span>
        </span>
      </div>

      <div @click="logOut" class="w-full text-3xl py-5 px-5 border border-x-0 border-cyan-300 hover:bg-sky-300 hover:text-gray-950 cursor-pointer">
        > Logout
      </div>
    </div>
  </div>
</template>
