<script setup>
  import HomeLogo from '@/components/HomeLogo.vue';
  import axios from 'axios';
  import router from '@/router';
  import { ref,reactive, watchEffect } from 'vue'

  const API = import.meta.env.VITE_LARAVEL_API;
  let buttonDisabled = ref(true)

  const loginForm = reactive([
    { email: '' },
    { pasword: '' },
    { remember: false }
  ])

  function preventLogin() {
    if (loginForm.email === '' || loginForm.password === '') {
      buttonDisabled = true
    } else {
      buttonDisabled = false
    }
  }

  async function login(){
    const loginData = {
      email: loginForm.email,
      password: loginForm.password,
      remember_me: loginForm.remember,
    }

    try {
      const loginResponse = await axios.post(API + 'auth/login', loginData)

      console.log(loginResponse.data)

      if (loginResponse.data.status === 'success') {
        router.push({ path: loginResponse.data.role + '/' + loginResponse.data.user_id + '/home'}) 
      } else {
        console.log(loginResponse.data.message)
        alert(loginResponse.data.message) 
      }
    } catch (error) {
      console.log(error.response.data)
      alert(error.response.data.message)
    }
  }

  watchEffect(() => {
    preventLogin()
  })
</script>

<template>
  <!-- Left: Image -->
  <div class="absolute hidden top-0 -z-10 w-screen h-screen bg-cover sm:block sm:bg-[url('/src/assets/HomePageBG.png')] opacity-10"></div>
  
  <HomeLogo />
  
  <!-- Right: Login Form -->
  <div class="bg-gray-700 h-screen w-full sm:w-1/2 p-8 right-0 absolute z-10">
    <h1 class="text-2xl font-semibold mb-4">Login</h1>
    <form @submit.prevent="login">

      <!-- Email Input -->
      <div class="mb-4 text-gray-600">
        <label class="block text-gray-600">Email</label>
        <input tabindex="0" type="text" v-model="loginForm.email" placeholder="Email" class="placeholder:text-gray-300 w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-blue-500" autocomplete="off">
      </div>

      <!-- Password Input -->
      <div class="mb-4 text-gray-600">
        <label for="password" class="block text-gray-600">Password</label>
        <input type="password" v-model="loginForm.password" placeholder="Password" class="placeholder:text-gray-300 w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-blue-500" autocomplete="off">
      </div>

      <!-- Remember Me Checkbox -->
      <div class="mb-4 flex items-center">
        <input type="checkbox" v-model="loginForm.remember" id="remember" name="remember" class="text-blue-500">
        <label for="remember" class="text-gray-600 ml-2">Remember Me</label>
      </div>

      <!-- Forgot Password Link -->
      <!-- <div class="mb-6 text-blue-500"> -->
      <!--   <RouterLink to="/forgot-password"> Forgot Password? </RouterLink> -->
      <!-- </div> -->
    
      <!-- Login Button -->
      <button :disabled="buttonDisabled" :class="{ 'bg-slate-300 hover:bg-slate-300': buttonDisabled }" type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-md py-2 px-4 w-full">Login</button>
    
    </form>
    <!-- Sign up  Link -->
    <div class="mt-6 text-blue-500 text-center">
      <RouterLink to="/signup"> Don't have an account? Sign Up Here </RouterLink>
    </div>
  </div>
</template>
