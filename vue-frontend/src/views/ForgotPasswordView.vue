<script setup lang="ts">
import { ref, watchEffect } from 'vue';
import axios from 'axios';
import HomeLogo from '@/components/HomeLogo.vue';

const email = ref('');
const emailError = ref(false);
const API = import.meta.env.VITE_LARAVEL_API;

async function submit() {
  try {
    const response = await axios.post(API + 'auth/forgot-password', {
      email: email.value,
    });

    console.log(response.data);
    alert(response.data.status);
  } catch (error) {
    console.error(error);
  }
}

function emailFormat() {
  const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
  if (emailRegex.test(email.value)) {
    emailError.value = false; 
  } else {
    emailError.value = true;
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
        <input tabindex="0" type="text" v-model="email" placeholder="Email"
          class="placeholder:text-gray-300 w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-blue-500"
          autocomplete="off" @input="emailFormat()">
      </div>

      <!-- Login Button -->
      <button type="submit"
        class="bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-md py-2 px-4 w-full">
        Reset Password
      </button>

    </form>
  </div>

</template>
