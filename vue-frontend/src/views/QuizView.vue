<script setup>
  import { ref, onMounted, watchEffect } from 'vue';
  import axios from 'axios';
  import TopBar from "@/components/TopBar.vue";
  import { useRoute } from 'vue-router'

  const API = import.meta.env.VITE_LARAVEL_API;
  const route = useRoute()

  let quizzes = ref([])
  let chosenAnswers = ref([])

  async function getQuizzes() {
    const res = await axios.get(API + 'quiz/' + route.params.quizId)
    quizzes.value = res.data
    console.log(quizzes.value)
  }

  function getOptions(options) {
    const separatedOptions = options.split(', ')
    return separatedOptions
  }

  function MCQAnswer(index, answer) {
    chosenAnswers.value.push({ question: index, answer: answer }) 
  }

  onMounted(() => {
    getQuizzes()
  })

  watchEffect(() => {
    console.log(chosenAnswers.value)
  })
</script>

<template>
  <TopBar />
  <div v-for="(question, qNum) in quizzes" :key="qNum"
    class="rounded-md border-2 mx-4 my-4 py-4 px-4 font-jetBrains">
    <!-- Question Component -->
    <div class="bg-transparent">
      <h1 class="text-2xl font-bold"> #{{ qNum + 1 }}</h1>
      <p>{{ question.description }}</p>

      <!-- Multi-choice Question -->
      <div v-if="question.options">
        <div v-for="(options, index) in getOptions(question.options)" :key="index">
          <input type="radio" :name="qNum" :value="index" v-model="chosenAnswers[qNum]">
          <label :for="options" class="px-2">
            {{ options }}
          </label>
        </div>
      </div>
      <!-- -------- -->

      <!-- Subjective Question -->
      <div v-else-if="!question.options && typeof(question.correct_answers) === 'string'">
        <input type="text" placeholder="Type your answer here..." class="text-black rounded-md px-2 my-2"> 
      </div>
      <!-- ----- -->

      <!-- True/False Question -->
      <div v-else-if="!question.options && typeof(question.correct_answer) === 'number'">
        <select name="true false" class="rounded-md text-black pr-4 my-2">
          <option value="0"> False </option>
          <option value="1"> True </option>
        </select>
      </div>
      <!-- ----- -->
      
    </div>
  </div>
  <div>
    <button>Cancel</button>
    <button>Submit answers</button>
  </div>
</template>
