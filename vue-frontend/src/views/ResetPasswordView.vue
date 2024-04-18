<script setup>
import { onMounted, ref } from 'vue';
import HomeLogo from '@/components/HomeLogo.vue';
import axios from 'axios';
import { useRoute, useRouter } from 'vue-router';

const API = import.meta.env.VITE_LARAVEL_API;
const route = useRoute()
const router = useRouter()
  
const emailError = ref(false)
const form = ref({
  email: '',
  password: '',
  confirmPsw: {
    psw: '',
    label: 'Confirm Password',
    labelColor: 'text-gray-600'
  },
  token: route.query.token
});

async function submit() {
  const data = {
    ...form.value,
    password_confirmation: form.value.confirmPsw.psw
  }
  try {
    const response = await axios.post(API + 'auth/reset-password', data);

    console.log(response.data);
    alert(response.data.status);
    router.push({ name: 'login' })
  } catch (error) {
    console.error(error);
  }
}

function emailFormat() {
  const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
  if (emailRegex.test(form.value.email)) {
    emailError.value = false; 
  } else {
    emailError.value = true;
  }
}

function checkPsw() {
  if (form.value.confirmPsw.psw !== form.value.password) {
    form.value.confirmPsw.label = 'Passwords do not match!'
    form.value.confirmPsw.labelColor = 'text-red-500'
  } else {
    form.value.confirmPsw.label = 'Confirm Password'
    form.value.confirmPsw.labelColor = 'text-gray-600'
  }
}

</script>
<template>

  <!-- Left: Image -->
  <div
    class="absolute hidden top-0 -z-10 w-screen h-screen bg-cover sm:block sm:bg-[url('/src/assets/HomePageBG.png')] opacity-10">
  </div>

  <HomeLogo />

  <!-- Right: Login Form -->
  <div class="bg-gray-700 h-screen w-full sm:w-1/2 p-8 right-0 absolute z-10">
    <h1 class="text-2xl font-semibold mb-4">Forgot Password</h1>
    <form @submit.prevent="submit">

      <!-- Email Input -->
      <div class="mb-4 text-gray-600">
        <label v-show="emailError === false" class="block text-gray-600">Email</label>
        <label v-show="emailError === true" class="block text-red-600">Invalid email format!</label>
        <input tabindex="0" type="text" v-model="form.email" placeholder="Email"
          class="placeholder:text-gray-300 w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-blue-500"
          autocomplete="off" @input="emailFormat()">
      </div>

      <!-- New Password Input -->
      <div class="mb-4 text-gray-600">
        <label for="password" class="block text-gray-600">Password</label>
        <input type="password" id="password" v-model="form.password" placeholder="Password" class="placeholder:text-gray-300 w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-blue-500" @change="checkPsw()" autocomplete="off">
      </div>
      
      <!-- Confirm New Password Input -->
      <div class="mb-4 text-gray-600">
        <label for="confirm-password" class="block" :class="form.confirmPsw.labelColor">{{ form.confirmPsw.label }}</label>
        <input type="password" id="confirm-password" v-model="form.confirmPsw.psw" placeholder="Confirm Password" class="placeholder:text-gray-300 w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-blue-500" @input="checkPsw()" autocomplete="off">
      </div>

      <!-- Reset password Button -->
      <button type="submit"
        class="bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-md py-2 px-4 w-full">
        Change Password
      </button>

    </form>
  </div>
</template>
