<script setup>
import { onMounted, ref } from 'vue'
import VueFeather from 'vue-feather'

const isToggled = ref(false)

function checkMode() {
  if (localStorage.getItem('theme') === 'dark') {
    isToggled.value = true
  } else {
    isToggled.value = false
  }
}

function setMode() {
  isToggled.value = !isToggled.value
  if (isToggled.value) {
    localStorage.setItem('theme', 'dark')
    document.body.classList.add('dark')
  } else {
    localStorage.setItem('theme', 'light')
    document.body.classList.remove('dark')
  }
}

onMounted(() => {
  checkMode();
})
</script>

<template>
  <label class="relative inline-flex items-center cursor-pointer" @change="setMode">
    <span class="flex w-24">
      <input type="checkbox" value="" class="sr-only peer" :checked="isToggled">
      <div class="relative w-11 h-6 rounded-full peer bg-gray-700 peer-focus:ring-4 peer-focus:ring-gray-300 dark:peer-focus:ring-teal-800 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-teal-600"></div>


      <div v-if="isToggled" class="text-white px-2">
        <vue-feather type="moon" stroke-width="2" fill="currentColor"></vue-feather>
      </div>

      <div v-else-if="!isToggled" class="text-gray-900 px-2">
        <vue-feather type="sun" stroke-width="2" fill="currentColor"></vue-feather>
      </div>
    </span>

  </label>
</template>
