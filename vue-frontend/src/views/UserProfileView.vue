<script setup>
  import TopBar from '@/components/TopBar.vue';
  import UserAvatar from '@/components/UserAvatar.vue';
  import NormalIcon from '@/components/icons/NormalIcon.vue';
  import NoDisturbIcon from '@/components/icons/NoDisturbIcon.vue';
  import InvisibleIcon from '@/components/icons/InvisibleIcon.vue';
  import Modal from '@/components/Modal.vue';
  import VueFeather from 'vue-feather';
  import axios from 'axios';
  import { ref, onMounted, warn } from 'vue';
  import { useRoute, useRouter } from 'vue-router';

  const API = import.meta.env.VITE_LARAVEL_API;
  const route = useRoute();
  const router = useRouter();
  const user = ref({});
  const userId = route.params.userId
  const userRole = route.params.userRole

  const studentPoints = ref(0)
  const studentBadges = ref([])

  const changeEmailModal = ref(false)
  const changeEmailPasswordInput = ref(false)
  const changeEmailPassword = ref('')
  const newEmail = ref('')
  const badgesModal = ref([false, false])

  const mode = ref('normal'); //default is normal

  async function getUserData() {
    const userData = await axios.get(API + 'user/?id=' + userId)
    if (userRole === 'student') {
      const badges = await axios.get(API + 'student/badges', { params: { id: userId } })
      studentBadges.value = badges.data
    }
    user.value = userData.data 
    mode.value = userData.data.mode; // Set the user's mode
    console.log(userData.data)
  }

  async function getPoints() {
    if (userRole !== 'student') return
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

  async function changeEmail() {
    try {
      const res = await axios.put(`${API}user/change-email`, { 
        email: newEmail.value,
        password: changeEmailPassword.value 
      });
      alert('Email updated successfully.');
      changeEmailModal.value = false;
      user.value.email = newEmail.value;
    } catch (error) {
      console.error('Error updating email:', error);
      alert(error.response.data.message);
    }
    changeEmailPasswordInput.value = false;
  }

  onMounted(() => {
    getUserData();
    getPoints();
  })
</script>

<template>
  <TopBar />

  <div class="bg-soft-white dark:bg-soft-black">
    <div class="flex justify-center">
      <div class="h-screen dark:bg-slate-700 bg-gray-100 shadow-lg dark:text-darkMode text-soft-black w-full sm:w-3/4 mx-auto py-8 justify-center">
        <div name="Info" class="flex flex-rows">
          <div class="w-1/2 md:w-1/4">
            <UserAvatar class="mx-8 my-8 rounded-full border border-sky-200" /> 
          </div>
          <span class="py-2 flex flex-col place-self-center overflow-hidden">
            <p class="text-4xl sm:text-6xl font-bold sm:my-4 mx-4">{{ user.name }}</p>
            <select v-model="mode" @change="updateMode" class="rounded-sm ml-4 inline-block text-black">
              <option value="normal">Normal</option>
              <option value="do not disturb">Do Not Disturb</option>
              <option value="invisible">Invisible</option>
            </select>
            <p class="text-2xl sm:text-4xl sm:my-4 mx-4">{{ user.email }}</p>
            <span class="flex flex-row gap-2 mx-4 font-jetBrains">
              <p class="w-fit text-xl sm:text-2xl px-2 rounded-md text-gray-900" :class="{'bg-green-400': userRole === 'student', 'bg-red-500': userRole === 'teacher'}" >{{ userRole }}</p>
              <p v-if="userRole === 'student'" class="w-fit text-xl sm:text-2xl px-2 bg-pink-300 rounded-md text-gray-900">{{ studentPoints }}pts</p>
            </span>
            <span class="flex flex-row gap-2 mx-4 font-jetBrains my-2">
              <button @click="badgesModal[index] = true" v-for="(badge, index) in studentBadges" class="w-fit text-xl sm:text-2xl px-2 hover:text-white hover:bg-yellow-700 bg-yellow-300 rounded-full text-gray-900" :title="badge.name">{{ badge.name }}</button>
            </span>
          </span>
        </div>

        <!-- functions -->
        <div class="flex flex-col">
          <RouterLink class="w-full text-3xl py-5 px-5 border border-x-0 border-cyan-300 hover:bg-sky-300 hover:text-gray-950 cursor-pointer" :to="{ name: 'forgotPassword' }" >
          > Change Password 
          </RouterLink>
          <div @click="changeEmailModal = true" class="w-full text-3xl py-5 px-5 border border-x-0 border-t-0 border-cyan-300 hover:bg-sky-300 hover:text-gray-950 cursor-pointer">
          > Change Email 
          </div>
          <div @click="logOut" class="w-full text-3xl py-5 px-5 border border-x-0 border-t-0 border-cyan-300 hover:bg-sky-300 hover:text-gray-950 cursor-pointer">
          > Logout
          </div>
        </div>
      </div>

      <Modal v-model="changeEmailModal">
        <div v-if="changeEmailPasswordInput" class="self-center rounded-md p-4 flex flex-col items-center justify-center dark:bg-gray-500 bg-gray-200 dark:text-darkMode text-gray-900 h-fit">
          <p class="text-2xl">Type your password to continue</p>
          <form @submit.prevent="changeEmail" class="w-full">
            <input type="password" v-model="changeEmailPassword" class="w-full p-2 my-2 text-black" placeholder="Password" />
            <input type="submit" class="rounded-md bg-blue-400 font-bold w-full mt-4 py-2 float-right cursor-pointer">
          </form>
        </div>

        <div v-else class="self-center rounded-md p-4 flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-500 h-fit dark:text-darkMode text-gray-900">
          <p class="text-2xl">What do you want to change your email to?</p>
          <input type="email" v-model="newEmail" class="w-full p-2 my-2 text-black" placeholder="New Email" />
          <button @click="changeEmailPasswordInput = true" class="rounded-md bg-blue-400 font-bold w-full mt-4 py-2 float-right">Submit</button>
        </div>
      </Modal>

      <!-- Badges Modals -->
      <Modal v-for="(badges, index) in studentBadges" v-model="badgesModal[index]">
        <div class="self-center rounded-md p-4 flex flex-col items-center justify-center dark:bg-gray-500 bg-gray-200 dark:text-darkMode text-gray-900 h-1/3 w-64">
          <vue-feather type="x" size="32" class="-mt-4 self-end cursor-pointer hover:text-gray-900" @click="badgesModal[index] = false"></vue-feather>
          <div class="rounded-full border-gray-800 border p-2 mt-4"><vue-feather type="star" size="64"></vue-feather></div>
          <div class="text-center mt-6">
            <p class="text-2xl font-bold">{{ badges.name }}</p>
            <p class="text-xl">{{ badges.description }}</p>
            <p class="text-md my-4">{{ badges.condition }}</p>
          </div>
        </div>
      </Modal>
    </div>
  </div>
</template>
