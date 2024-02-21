<script setup>
  import { ref } from "vue";

  const correctAnswer = ref('True')
</script>
<template>
  <div class="mx-4 flex flex-col">
    <!-- Question Title -->
    <input type="text" placeholder="Type Question here..." class="bg-transparent w-full">

    <select v-model="correctAnswer" class="float-right text-slate-600 my-2 w-1/2">
      <option value="True">True</option>
      <option value="False">False</option>
    </select>
  </div>
</template>