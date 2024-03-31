<script setup>
  import { onMounted, ref } from 'vue' 
  import axios from 'axios';
  import { useRoute } from 'vue-router'

  const quiz = ref([])
  const quizAnswers = ref('')
  const route = useRoute()

  const API = import.meta.env.VITE_LARAVEL_API;
  async function getQuizResult() {
    const data = {
      quiz_id: route.query.quizId
    } 
    const res = await axios.get(API + 'quiz/answer-get', { params: data })
    quizAnswers.value = JSON.parse(res.data)
    console.log(quizAnswers.value)
  }

  async function getQuizData() {
    const data = {
      quiz_id: route.query.quizId
    }
    const res = await axios.get(API + 'quiz/', { params: data })
    quiz.value = res.data
    console.log(quiz.value)
  }

  function getOptions(options) {
    const separatedOptions = options.split(', ')
    return separatedOptions
  }

  function isCorrectAnswer(qNum) {
    const questions = quiz.value[0]
    const userAnswer = quizAnswers.value[qNum].answer
    const correctAnswer = questions[qNum].correct_answer             
    const correctAnswers = questions[qNum].correct_answers

    if (questions[qNum].type === 'subjective' && questions[qNum].case_sensitive == 0) {   // for case insensitive SUBJECTIVE QUESTIONs 
      if (userAnswer.toLowerCase() === correctAnswers.toLowerCase()) return true
      else return false
    } 
    else {  // everything else doesn't need specific checks 
      if (userAnswer == correctAnswer || userAnswer == correctAnswers) return true              
      else return false
    }
  }

  onMounted(() => {
    getQuizData()
    getQuizResult()
  })
</script>
<template>
  <div v-for="(question, qNum) in quiz[0]" :key="qNum" class="rounded-md border-2 mx-4 my-4 py-4 px-4 font-jetBrains">

    <div class="bg-transparent">
      <h1 class="text-2xl font-bold"> #{{ qNum + 1 }}</h1>
      <div class="flex flex-row gap-x-12">
        <p>{{ question.description }}</p>
        <p v-if="question.case_sensitive == 1" class="text-red-500">* Case sensitive</p>
      </div>

      <!-- Multi-choice Question -->
      <div v-if="question.type === 'multi_choice'"> 
        <p class="text-white" :class="{ 'text-green-500': isCorrectAnswer(qNum) }" v-cloak> You chose: {{ quizAnswers[qNum].answer }}</p>
        <p v-if="!isCorrectAnswer(qNum)" class="text-white"> Correct Answer: {{ question.correct_answers }}</p>
        <div v-for="(option, index) in getOptions(question.options)" :key="index">
          <label :for="option + qNum" class="px-2">
            • {{ option }}
          </label>
        </div>
      </div>
      <!-- -------- -->

      <!-- Subjective Question -->
      <div v-else-if="question.type === 'subjective'">
        <p class="text-red-500" :class="{ 'text-green-500': isCorrectAnswer(qNum) }" v-cloak> 
          Your answer: {{ quizAnswers[qNum].answer }}
        </p>
        <p v-if="!isCorrectAnswer(qNum)" class="text-white"> Correct Answer: {{ question.correct_answers }}</p>
      </div>
      <!-- ----- -->

      <!-- True/False Question -->
      <div v-else-if="question.type === 'true_false'">
        <!-- conversion from 1 to true & 0 to false needs to be done cuz SQL boolean -->
        <div class="text-red-500" :class="{ 'text-green-500': isCorrectAnswer(qNum) }" v-cloak>
          <p v-if="quizAnswers[qNum].answer == 1"> You chose: True </p>
          <p v-else-if="quizAnswers[qNum].answer == 0"> You chose: False </p>
        </div>
        <!-- same thing here -->
        <div v-if="!isCorrectAnswer(qNum)" class="text-white">
          <p v-if="question.correct_answer == 1"> Correct Answer: True </p>
          <p v-else-if="question.correct_answer == 0"> Correct Answer: False </p>
        </div>
      </div>
      <!-- ----- -->
    </div>      
  </div>
</template>

