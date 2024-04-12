<script setup>
  import { onBeforeMount, onUpdated, ref } from 'vue' 
  import axios from 'axios';
  import { useRoute } from 'vue-router'
  import TopBar from '@/components/TopBar.vue';

  const quiz = ref([])
  const userAnswers = ref([])
  const userCorrectAnswers = ref(0)
  const submittedTime = ref()
  const route = useRoute()
  const userRole = route.params.userRole 

  const API = import.meta.env.VITE_LARAVEL_API;

  async function getQuizResult() {
    const data = {
      quiz_id: route.query.quizId
    } 
    const res = await axios.get(API + 'quiz/answer-get', { params: data })
    userAnswers.value = JSON.parse(res.data.user_answers)
    submittedTime.value = new Date(res.data.timestamp).toLocaleString()
  }

  async function getQuizData() {
    const data = {
      quiz_id: route.query.quizId
    }
    const res = await axios.get(API + 'quiz/', { params: data })
    quiz.value = res.data
  }

  function getOptions(options) {
    const separatedOptions = options.split(', ')
    return separatedOptions
  }

  function isCorrectAnswer(qNum) {
    const questions = quiz.value[0]
    const userAnswer = userAnswers.value.find(answer => answer.questionNum == qNum + 1).answer
    const correctAnswer = questions[qNum].correct_answer             
    const correctAnswers = questions[qNum].correct_answers

    if (questions[qNum].type === 'subjective' && questions[qNum].case_sensitive == 0) {   // for case insensitive SUBJECTIVE QUESTIONs 
      if (userAnswer.toLowerCase() === correctAnswers.toLowerCase()) return true 
      else return false 
    } 
    else if (questions[qNum].type === 'multi_choice' && questions[qNum].correct_answers.includes(',')) {  // for multi-answer mcq
      const correctAnswersArr = questions[qNum].correct_answers.split(', ')
      for (let i = 0; i < correctAnswersArr.length; i++) {
        if (userAnswer[i] === correctAnswersArr[i]) return true 
        else return false
      }
    }
    else {  // everything else doesn't need specific checks 
      if (userAnswer == correctAnswer || userAnswer == correctAnswers) return true  
      else return false 
    }
  }

  function getUserCorrectAnswers() {
    userCorrectAnswers.value = document.querySelectorAll('p.text-green-500').length + document.querySelectorAll('div.text-green-500').length
    console.log('function is called')
  }

  async function rewardPoints() {
    if (userCorrectAnswers.value === 0) return
    else {
      const data = {
        correct_answers: userCorrectAnswers.value,
        quiz_id: route.query.quizId
      }
      const res = await axios.post(API + 'quiz/reward-points/', data)
    }
  }

  function alertPoints() {
    if (route.params.userRole === 'student' && userCorrectAnswers.value !== 0) {
      alert('Congratulations! You have earned ' + (userCorrectAnswers.value * 100) + ' points!')
    }
  }

  onBeforeMount(async () => {
    await getQuizData()
    await getQuizResult()
    await rewardPoints()
    alertPoints()
  })

  onUpdated(() => {
    getUserCorrectAnswers()
  })
</script>
<template>
  <TopBar />
  <div class="px-4 py-2">
    <h1 class="text-3xl font-bold">{{ quiz['quiz_name'] ? quiz['quiz_name']: '' }}</h1>
    <p class="text-lg">Submitted at: {{ submittedTime }}</p>
    <p class="text-lg">Score: {{ userCorrectAnswers }}/{{ quiz[0] ? quiz[0].length: 0 }}</p>
    <p v-if="userRole==='student'"> You've earned: {{ userCorrectAnswers * 100 }} points </p>
  </div>
  <div v-for="(question, qNum) in quiz[0]" :key="qNum" class="rounded-md border-2 mx-4 my-4 py-4 px-4 font-jetBrains">

    <div class="bg-transparent">
      <h1 class="text-2xl font-bold"> #{{ qNum + 1 }}</h1>
      <div class="flex flex-row gap-x-12">
        <p class="border-b mb-2">{{ question.description }}</p>
        <p v-if="question.case_sensitive == 1" class="text-red-500">* Case sensitive</p>
      </div>

      <!-- Multi-choice Question -->
      <div v-if="question.type === 'multi_choice'"> 
        <p :class="{ 'text-green-500': isCorrectAnswer(qNum), 'text-red-500': !isCorrectAnswer(qNum) }" v-cloak> You chose: {{ userAnswers[qNum].answer.toString() }}</p>
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
        <p :class="{ 'text-green-500': isCorrectAnswer(qNum), 'text-red-500': !isCorrectAnswer(qNum) }" v-cloak> 
          Your answer: {{ userAnswers[qNum].answer }}
        </p>
        <p v-if="!isCorrectAnswer(qNum)" class="text-white"> Correct Answer: {{ question.correct_answers }}</p>
      </div>
      <!-- ----- -->

      <!-- True/False Question -->
      <div v-else-if="question.type === 'true_false'">
        <!-- conversion from 1 to true & 0 to false needs to be done cuz SQL boolean -->
        <div :class="{ 'text-green-500': isCorrectAnswer(qNum), 'text-red-500': !isCorrectAnswer(qNum) }" v-cloak>
          <p v-if="userAnswers[qNum].answer == 1"> You chose: True </p>
          <p v-else-if="userAnswers[qNum].answer == 0"> You chose: False </p>
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

