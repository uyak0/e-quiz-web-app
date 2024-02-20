<script setup>
  import TopBar from "@/components/TopBar.vue";

  const props = defineProps({
    question: String,
    answers: Array
  })
</script>
<template>
  <div class="mx-4 flex flex-col">
    <!-- Question Title -->
    <input type="text" placeholder="Type Question here..." class="bg-transparent w-full">

    <!-- Correct answers -->
    <div>
      <input type="text" placeholder="Type correct answer here..." class="bg-transparent">
      <button class="rounded-full border-2 border-dotted"> + </button>
    </div>
    <!-- Other answers -->
    <input type="text" placeholder="Type answer 2 here..." class="bg-transparent">
    <input type="text" placeholder="Type answer 3 here..." class="bg-transparent">
  </div>
</template>