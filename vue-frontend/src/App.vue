<script setup>
import { RouterLink, RouterView } from 'vue-router'
import HelloWorld from './components/HelloWorld.vue'

</script>

<template>
  <nav class="font-jetBrains top-0 left-0 absolute w-full">
    <RouterLink to="/" class="text-white hover:bg-transparent text-3xl px-2">E-Quizz</RouterLink>
    <RouterLink to="/newview" class="float-right bg-red-500 text-white border-0 rounded-md text-2xl px-3 mx-2 my-2">Login</RouterLink>
    <RouterLink to="/about" class="float-right"></RouterLink>
  </nav>  
  <RouterView />
</template>

