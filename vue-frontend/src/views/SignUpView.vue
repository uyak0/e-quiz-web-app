<script setup>
  import HomeLogo from '@/components/HomeLogo.vue';
  import axios from 'axios';
  import { onMounted, ref, watchEffect, reactive } from 'vue';

  const username = ref('')
  const email = ref('')
  const password = ref('')
  const role = ref('student')
  const buttonDisabled = ref(true) 

  const API = import.meta.env.VITE_LARAVEL_API;

  const confirmPsw = reactive({
    value: '',
    label: 'Confirm Password',
    labelColor: 'text-gray-600',
  });

  function ConfirmPasswordLabel() {
    if (confirmPsw.value !== password.value) {
      confirmPsw.label = 'Passwords do not match!'
      confirmPsw.labelColor = 'text-red-500'
    } else {
      confirmPsw.label = 'Confirm Password'
      confirmPsw.labelColor = 'text-gray-600'
    }
  }

  function preventSignUp() {
    const inputs = [username, email, password, confirmPsw.value];
    const isAllInputsFilled = inputs.every(input => input !== '');
    
    if (isAllInputsFilled) {
      buttonDisabled.value = false;
    } else {
      buttonDisabled.value = true;
    }
  }

  function Register() {
    const data = {
      username: username.value,
      email: email.value,
      password: password.value,
      role: role.value
    }

    axios.post(API + '/auth/register', data)
      .then(response => {
        console.log(response.data)
      })
      .catch(error => {
        console.log(error)
      })
  }

  watchEffect(() => {
    ConfirmPasswordLabel()
  })
</script>

<template>
  <div class="absolute top-0 -z-10 w-screen h-screen bg-cover bg-[url('/src/assets/HomePageBG.png')] opacity-10"></div>
  <HomeLogo />

  <!-- Right: Sign Up Form -->
  <div class="bg-gray-700 h-screen lg:p-36 md:p-52 sm:20 p-8 w-1/2 right-0 absolute lg:w-1/2 z-10">
    <h1 class="text-2xl font-semibold mb-4">Sign Up</h1>
    <form @submit="signup">
      
      <!-- Username -->
      <div class="mb-4 text-gray-600 ">
        <label class="block text-gray-600">Username</label>
        <input type="text" v-model="username" placeholder="Username" class="placeholder:text-gray-300 w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-blue-500" autocomplete="off">
      </div>

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
        <label for="confirm-password" class="block text-gray-600" :class="confirmPsw.labelColor">{{ confirmPsw.label }}</label>
        <input type="password" id="confirm-password" v-model="confirmPsw.value" placeholder="Confirm Password" class="placeholder:text-gray-300 w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-blue-500" autocomplete="off">
      </div>
      
      <!-- Select Role to register as -->
      <select v-model="role" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-blue-500 text-gray-700">
        <option value="student">Student</option>
        <option value="teacher">Teacher</option>
      </select>

      <!-- Sign Up Button -->
      <button :disabled="buttonDisabled" type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-md my-8 py-2 px-2 w-1/2 float-right">Sign Up</button>
      
      <!-- Login Link -->
      <div class="float-left mb-4 text-blue-500">
        <RouterLink to="/login"> Already have an account? Login here. </RouterLink>
      </div>
    </form>
  </div>
</template>
