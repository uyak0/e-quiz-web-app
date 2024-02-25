<script setup>
  import ToggleTheme from './ToggleTheme.vue';

  const pageName = defineModel()
</script>

<template>
  <div class="sticky font-jetBrains flex flex-rows text-2xl justify-between px-2 py-1 bg-gray-600 place-items-center">
    <RouterLink to="/"> E-Quizz </RouterLink>
    
    <!-- Page Name -->
    <div class="font-firaSans font-bold">
      {{ pageName }}
    </div>
    
    <div name="theme and user" class="flex flex-row gap-10">
      <div class="place-items-center place-self-center">
        <ToggleTheme @dark-toggle="darkToggle"/> 
      </div>

      <!-- Rounded Avatar Default -->
      <div class="relative w-10 h-10 overflow-hidden bg-gray-100 rounded-full dark:bg-gray-600">
          <svg class="absolute w-12 h-12 text-gray-400 -left-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
      </div>
    </div>

 
  </div>
</template>