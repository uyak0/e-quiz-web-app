<script setup>
  import { ref, watchEffect } from "vue";
  import VueFeather from 'vue-feather';

  const emit = defineEmits([
    'addDesc',
    'addAnswer', 
    'removeAnswer',
    'addOption',
    'removeOption',
  ]);

  const questionDesc = ref('')
  const correctAnswers = ref([''])
  const options = ref([''])

  function addCorrectAnswer(index, e) {
    correctAnswers.value.splice(index, 1, e.target.value)
    emit('addAnswer', correctAnswers.value)
  }

  function addOption(index, e) {
    options.value.splice(index, 1, e.target.value)
    emit('addOption', options.value)
  }
  
  watchEffect(() => {
  })
</script>
<template>
  <div class="mx-4 flex flex-col">
    <!-- Question Title -->
    <input type="text" @change="emit('addDesc', questionDesc)" v-model="questionDesc" placeholder="Type Question here..." class="placeholder:text-slate-600 bg-transparent w-full">

    <!-- Correct answers -->
    <div v-for="(correctAnswer, index) in correctAnswers" :key="index" class="my-1 flex-row flex">
      <input type="text" @change="addCorrectAnswer(index, $event)" placeholder="Type correct answer here..." class="overflow-ellipsis placeholder:text-slate-600 bg-transparent ">
      <div class="flex-row flex mx-4 gap-x-2">
        <vue-feather name="add-button" class="cursor-pointer" @click="correctAnswers.length++" type="plus-circle" />
        <vue-feather v-if="index > 0" name="dlt-button" class="cursor-pointer" @click="correctAnswers.length--" type="x-circle" />
      </div>
    </div>

    <!-- Other answers -->
    <div v-for="(option, index) in options" :key="index" class="my-1 flex flex-row">
      <input type="text" @change="addOption(index, $event)" placeholder="Type other option here..." class="overflow-ellipsis placeholder:text-slate-600 bg-transparent ">
      <div class="flex flex-row mx-4 gap-x-2">
        <vue-feather class="cursor-pointer" @click="options.length++" type="plus-circle" />
        <vue-feather v-if="index > 0" class="cursor-pointer" @click="options.length--" type="x-circle" />
      </div>
    </div>
  </div>
</template>
