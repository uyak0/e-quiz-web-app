<script setup>
import { RouterLink, RouterView } from 'vue-router'
import HelloWorld from './components/HelloWorld.vue'

</script>

<template>
  <nav class="top-0 absolute z-50 w-full">
    <RouterLink to="/">Home</RouterLink>
    <RouterLink to="/about">About</RouterLink>
    <RouterLink to="/newview">New View</RouterLink>
  </nav>  
  <RouterView />
</template>

