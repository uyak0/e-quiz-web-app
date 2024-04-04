<script setup>
  import { ref, watchEffect } from "vue";

  const emit = defineEmits([
    'addAnswer', 
    'removeAnswer',
    'addOption',
    'removeOption',
  ]);

  const correctAnswers = ref([''])
  const options = ref([''])

  function addCorrectAnswer(index, e) {
    correctAnswers.value.splice(index, 1, e.target.value)
    console.log(correctAnswers.value)
    emit('addAnswer', correctAnswers)
  }


  function addOption() {
    options.value.length++
    emit('addOption')
  }
  
  watchEffect(() => {
  })
</script>
<template>
  <div class="mx-4 flex flex-col">
    <!-- Question Title -->
    <input type="text" placeholder="Type Question here..." class="bg-transparent w-full">

    <!-- Correct answers -->
    <div v-for="(correctAnswer, index) in correctAnswers" :key="index">
      <input type="text" @change="addCorrectAnswer(index, $event)" placeholder="Type correct answer here..." class="bg-transparent w-2/5">
      <button @click="correctAnswers.length++" class="rounded-full border-2 border-dotted px-2 mx-2 text-3xl"> + </button>
    </div>

    <!-- Other answers -->
    <div v-for="(option, index) in options" :key="index">
      <input type="text" placeholder="Type other option here..." class="bg-transparent w-2/5">
      <button @click="addOption" class="rounded-full border-2 border-dotted px-2 mx-2 text-3xl"> + </button>
    </div>
  </div>
</template>
