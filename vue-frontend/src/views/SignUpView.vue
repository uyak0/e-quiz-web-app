<script setup>
  import HomeLogo from '@/components/HomeLogo.vue';
  import axios from 'axios';
  import { ref } from 'vue';

  const email = ref('')
  const password = ref('')
  // const api = process.env.LARAVEL_API
  const confirm_psw = ref('')
  const confirm_psw_label = ref('Confirm Password')
  
  async function signup() {
    if (confirm_psw != password) {
      confirm_psw_label = 'Passwords do not match'
    }
    else {
      await axios.post(api + 'auth/signup', {
        email: email,
        password: password
      })
    }
  }
</script>

<template>
  <div class="absolute top-0 -z-10 w-screen h-screen bg-cover bg-[url('/src/assets/HomePageBG.png')] opacity-10"></div>
  <HomeLogo />

  <!-- Right: Sign Up Form -->
  <div class="bg-gray-700 h-screen lg:p-36 md:p-52 sm:20 p-8 w-1/2 right-0 absolute lg:w-1/2 z-10">
    <h1 class="text-2xl font-semibold mb-4">Sign Up</h1>
    <form :submit="signup">

      <!-- Email Input -->
      <div class="mb-4 text-gray-600 ">
        <label class="block text-gray-600">Email</label>
        <input type="text" v-model="email" placeholder="Email" class="placeholder:text-gray-300 w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-blue-500" autocomplete="off">
      </div>

      <!-- Password Input -->
      <div class="mb-4 text-gray-600">
        <label for="password" class="block text-gray-600">Password</label>
        <input type="password" id="password" v-model="password" placeholder="Password" class="placeholder:text-gray-300 w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-blue-500" autocomplete="off">
      </div>
      
      <!-- Confirm Password Input -->
      <div class="mb-4 text-gray-600">
        <label for="confirm-password" class="block text-gray-600">{{ confirm_psw_label }}</label>
        <input type="password" id="confirm-password" v-model="confirm_psw" placeholder="Confirm Password" class="placeholder:text-gray-300 w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-blue-500" autocomplete="off">
      </div>
      
      <!-- Sign Up Button -->
      <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-md my-8 py-2 px-2 w-1/2 float-right">Sign Up</button>
      
      <!-- Login Link -->
      <div class="float-left mb-4 text-blue-500">
        <RouterLink to="/login"> Already have an account? Login here. </RouterLink>
      </div>
    </form>
  </div>
</template>
