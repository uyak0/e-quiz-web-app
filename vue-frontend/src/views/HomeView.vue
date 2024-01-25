<script setup>
import HomeLogo from '@/components/HomeLogo.vue';

// import TheWelcome from '../components/TheWelcome.vue'
// import axios from 'axios'
// import { onMounted, reactive } from 'vue';

// const state = reactive ({
//   students: [],
// })

// async function getData() {
//   await axios.get('http://localhost:8000/api/students')
//   .then(response => {
//     console.log(response.data)
//     state.students = response.data
//   })
//   .catch(error => {
//     console.log(error)
//   });
// }

// onMounted(getData)
</script>

<template>
  <div class="absolute top-0 z-0 w-screen h-screen bg-cover bg-[url('/src/assets/HomePageBG.png')] opacity-10"></div>

  <nav class="font-jetBrains top-0 left-0 absolute w-full">
    <RouterLink to="/login" class="hover:bg-red-800 float-right bg-red-500 text-white font-bold border-0 rounded-md text-2xl px-3 mx-2 my-2">Login</RouterLink>
    <RouterLink to="/signup" class="hover:bg-slate-800 float-right text-white border-0 rounded-md text-xl px-3 mx-2 my-3">Sign Up</RouterLink>
    <RouterLink to="/about" class="hover:bg-slate-800 float-right text-white border-0 rounded-md text-xl px-3 mx-2 my-3">About Us</RouterLink>
  </nav>

  <div class="flex flex-col justify-center h-screen px-6">
    <div class="wrapper font-jetBrains font-bold">
      <h1 class="text-9xl text-white">E-Quizz</h1><br>
      <h3 class="text-3xl text-slate-400">Your personal hub of Learning.</h3>
    </div>
  </div>
</template>
