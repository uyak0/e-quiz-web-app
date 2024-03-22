<script setup>
  import { ref } from "vue";
  const correctAnswer = ref('')
</script>

<template>
  <!-- Question Title -->
  <input type="text" placeholder="Type Question here..." class="bg-transparent w-full">
  <div class="flex flex-rows gap-10">
    <input type="text" placeholder="Type the correct answer here (if applicable)" class="bg-gray-500 rounded-md px-2 w-1/2">
    <span class="flex gap-2">
      <input type="radio" id="case-sensitive" v-model="correctAnswer">
      <label for="case-sensitive">Case sensitive?</label>
    </span>
  </div>
</template>
