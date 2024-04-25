<script setup>
import { onMounted } from 'vue';
import { RouterView } from 'vue-router'

function checkMode() {
  if (localStorage.getItem('theme') === 'dark') {
    document.body.classList.add('dark')
  } else {
    document.body.classList.remove('dark')
  }
}

onMounted(() => {
  checkMode();
})
</script>

<template>
  <RouterView />
</template>
