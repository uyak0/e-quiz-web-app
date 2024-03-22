<script setup>
  import { ref } from 'vue';
  
  const classroomProps = defineModel()
</script>

<template>
  <RouterLink :to="{ path: 'classroom/' + classroomProps.id }" class="hover:-translate-y-1 sm:hover:scale-105 ease-in-out duration-200 my-4 mx-4 rounded-md border-black bg-slate-100 text-black h-48 sm:w-48 overflow-hidden flex-rows flex sm:flex-col">
    <div class="banner h-full sm:h-20 px-10 bg-gray-500 ">
      some img
    </div>
    <div class="text-2xl mx-2">
      <p>{{ classroomProps.name }}</p>
    </div>
  </RouterLink>
</template>
