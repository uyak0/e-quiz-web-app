<script setup>
  import { ref } from "vue";

  const correctAnswers = ref([
    { answer: '' }    // array with answer as key
  ])
  const options = ref([
    { option: '' }    // same thing
  ])

  // button for adding another correct option
  const addCorrectAnswer = () => {
    correctAnswers.value.push({ answer: '' })   // pushes empty answer into the array
  }

  const addOption = () => {
    options.value.push({ option: '' })
  }
</script>
<template>
  <div class="mx-4 flex flex-col">
    <!-- Question Title -->
    <input type="text" placeholder="Type Question here..." class="bg-transparent w-full">

    <!-- Correct answers -->
    <div v-for="(correctAnswer, index) in correctAnswers" :key="index">
      <input type="text" v-model="correctAnswer.answer" placeholder="Type correct answer here..." class="bg-transparent w-2/5">
      <button @click="addCorrectAnswer" class="rounded-full border-2 border-dotted px-2 mx-2 text-3xl"> + </button>
    </div>

    <!-- Other answers -->
    <div v-for="(option, index) in options" :key="index">
      <input type="text" placeholder="Type other option here..." class="bg-transparent w-2/5">
      <button @click="addOption" class="rounded-full border-2 border-dotted px-2 mx-2 text-3xl"> + </button>
    </div>
  </div>
</template>