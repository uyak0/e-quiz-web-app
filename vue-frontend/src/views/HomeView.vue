<script setup>
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
  <nav class="font-jetBrains top-0 left-0 absolute w-full">
    <RouterLink to="/login" class="float-right bg-red-500 text-white font-bold border-0 rounded-md text-2xl px-3 mx-2 my-2">Login</RouterLink>
    <RouterLink to="/signup" class="float-right text-white border-0 rounded-md text-xl px-3 mx-2 my-3">Sign Up</RouterLink>
    <RouterLink to="/about" class="float-right text-white border-0 rounded-md text-xl px-3 mx-2 my-3">About Us</RouterLink>
  </nav>

  <div class="h-full absolute left-0 font-jetBrains text-9xl font-extrabold">
    E-Quizz
  </div>
</template>
