<script setup>
  import { ref } from 'vue';
  
  const classroomProps = defineModel()
</script>

<template>
  <RouterLink :to="{ path: '/classroom/' + classroomProps.id }" class="hover:scale-105 ease-in-out duration-100 my-4 mx-4 rounded-md border-black bg-slate-100 text-black h-48 w-48 overflow-hidden">
    <div class="banner h-20 bg-gray-500 ">
      some img
    </div>
    <div class="text-2xl mx-2">
      <p>{{ classroomProps.name }}</p>
    </div>
  </RouterLink>
</template>
