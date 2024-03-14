<script setup>
  import HomeLogo from '@/components/HomeLogo.vue';
  import axios from 'axios';
  import router from '@/router';
  import { ref,reactive } from 'vue'

  const API = import.meta.env.VITE_LARAVEL_API;

  const loginForm = reactive([
    { email: '' },
    { pasword: '' },
    { remember: false }
  ])

  async function login(){
    const loginData = {
      email: loginForm.email,
      password: loginForm.password,
      remember_me: loginForm.remember
    }

    await axios.get('/sanctum/csrf-cookie')
      .then (response => {
        // console.log(response)
        // console.log('Cookie Acquired')
      })
    
    await axios.post(API + 'auth/login', loginData)
      .then(response => {
        console.log(response.data)
        localStorage.setItem('remember_token', response.data.rememberToken)
        router.push({ path: '/' + response.data.role + '/' + response.data.user_id + '/home' })
        // router.push({ path: '/'})
        // if(response.data === 'success'){
        //   router.push({ path: '/'})
        // }
      })
      .catch(error => {
        console.log(error.response.data.message)
        alert(error.response.data.message)
      })
  }
</script>

<template>
  <!-- Left: Image -->
  <div class="absolute top-0 -z-10 w-screen h-screen bg-cover bg-[url('/src/assets/HomePageBG.png')] opacity-10"></div>
  
  <HomeLogo />
  
  <!-- Right: Login Form -->
  <div class="bg-gray-700 h-screen lg:p-36 sm:20 p-8 w-1/2 right-0 absolute lg:w-1/2 z-10">
    <h1 class="text-2xl font-semibold mb-4">Login</h1>
    <form @submit.prevent="login">

      <!-- Email Input -->
      <div class="mb-4 text-gray-600">
        <label class="block text-gray-600">Email</label>
        <input type="text" v-model="loginForm.email" placeholder="Email" class="placeholder:text-gray-300 w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-blue-500" autocomplete="off">
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
      <div class="mb-6 text-blue-500">
        <RouterLink to="/forgot-password"> Forgot Password? </RouterLink>
      </div>
    
      <!-- Login Button -->
      <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-md py-2 px-4 w-full">Login</button>
    
    </form>
    <!-- Sign up  Link -->
    <div class="mt-6 text-blue-500 text-center">
      <RouterLink to="/signup"> Don't have an account? Sign Up Here </RouterLink>
    </div>
  </div>
</template>
