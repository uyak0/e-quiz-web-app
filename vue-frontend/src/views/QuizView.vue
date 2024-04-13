<script setup>
  import { ref, onMounted, reactive, watchEffect, onBeforeMount } from 'vue';
  import { useRoute, useRouter } from 'vue-router'
  import axios from 'axios';

  const API = import.meta.env.VITE_LARAVEL_API;
  const route = useRoute()
  const router = useRouter()

  let quiz = ref([])
  let userAnswers = ref([])
  let checkedAnswers = ref(0)

  function shuffleOptions(options) {
    for (let i = options.length - 1; i > 0; i--) {
      let j = Math.floor(Math.random() * (i + 1));
      [options[i], options[j]] = [options[j], options[i]];
    }
    return options 
  }

  function getOptions(questions) {
    for (let qNum in questions) {
      let question = questions[qNum]
      if (question.type !== 'multi_choice') continue 
      else {
        const separatedOptions = question.options.split(', ')
        question.options = shuffleOptions(separatedOptions)
      }
    }
  }

  async function getQuizData() {
    const data = {
      quiz_id: route.params.quizId
    }
    const res = await axios.get(API + 'quiz/', { params: data })
    quiz.value = res.data
    getOptions(quiz.value[0])
  }

  function changeAnswer(event, qNum) {
    const answer = event.target.value
    const existingAnswer = userAnswers.value.find((answer) => answer.questionNum === qNum)
    const index = userAnswers.value.indexOf(existingAnswer)
    const question = quiz.value[0][qNum - 1]
    const multiAnswer = event.target.type === 'checkbox'
    const singleAnswer = event.target.type === 'radio'
    console.log(userAnswers.value)

    // this code fucking suck
    if (!existingAnswer) {
      if (multiAnswer || singleAnswer) {   
        userAnswers.value.push({ questionNum: qNum, answer: [answer] })     //this fucking suck
      }
      else {
        userAnswers.value.push({ questionNum: qNum, answer: answer })
      }
    } 
    else { // if answer already exists
      if (event.target.type == 'checkbox' && event.target.checked) {
        userAnswers.value[index].answer.push(answer)
      }
      else if (multiAnswer && !event.target.checked) {
        userAnswers.value[index].answer.splice(userAnswers.value[index].answer.indexOf(answer), 1)
      }
      else if (question.type === 'subjective' && answer == '') {
        userAnswers.value.splice(index, 1)
      }
      else if (singleAnswer) {
        userAnswers.value.splice(index, 1, { questionNum: qNum, answer: [answer] })
      }
    }
  }

  function isDone(qNum) {
    return this.userAnswers.some(answer => answer.questionNum === qNum);
  }

  function quit() {
    let res = confirm("Are you sure you want to leave? Don't worry, you can still come back!")
    if (res) {
      localStorage.setItem('prevUserAnswers', JSON.stringify(userAnswers.value))
      router.push({ name: 'classroom' })
    }
  }

  async function submit() {
    let confirmSubmit;
    const questions = quiz.value[0]
    
    if (userAnswers.value.length !== quiz.value[0].length) {
      alert('Please answer all questions before submitting!')
    } else {
      confirmSubmit = confirm('Are you sure you want to submit your answers?')
    }

    if (confirmSubmit) {
      const data = {
        quiz_id: route.params.quizId,
        user_id: route.params.userId,
        user_answers: JSON.stringify(userAnswers.value)
      }
      const res = await axios.post(API + 'quiz/answer-submit', data)

      router.push({ 
        name: 'quizResult', 
        query: { quizId: route.params.quizId }
      })
    }
  }

  onBeforeMount(() => {
    if (localStorage.getItem('prevUserAnswers')) {
      userAnswers.value = JSON.parse(localStorage.getItem('prevUserAnswers'))
      localStorage.removeItem('prevUserAnswers')
    }
  })

  onMounted(() => {
    getQuizData()
  })
</script>

<template>
  <h1 class="text-4xl p-4">{{quiz.quiz_name}}</h1>
  <div v-for="(question, qNum) in quiz[0]" :key="qNum" class="rounded-md border-2 mx-4 my-4 py-4 px-4 font-jetBrains">
    <!-- Question Component -->
    <div class="bg-transparent">
      <h1 class="text-2xl font-bold" :class="{ 'text-green-400': isDone(qNum + 1) }"> #{{ qNum + 1 }}</h1>
      <div class="flex flex-row gap-x-12">
        <p>{{ question.description }}</p>
        <p v-if="question.case_sensitive == 1" class="text-red-500">* Case sensitive</p>
      </div>

      <!-- Multi-choice Question -->
      <div v-if="question.type === 'multi_choice'">

        <!-- Multi-answer  -->
        <div v-if="question.correct_answers.includes(',')">
          <div v-for="(option, index) in question.options" :key="index">
            <input type="checkbox" :id="option + qNum" :name="qNum" :value="option" @input="changeAnswer($event, qNum + 1)">
            <label :for="option + qNum" class="px-2">
              {{ option }}
            </label>
          </div>
        </div>

        <!-- Single-answer -->
        <div v-else>
          <div v-for="(option, index) in question.options" :key="index">
            <input type="radio" :id="option + qNum" :name="qNum" :value="option" @input="changeAnswer($event, qNum + 1)">
            <label :for="option + qNum" class="px-2">
              {{ option }}
            </label>
          </div>
        </div>

      </div>
      <!-- -------- -->

      <!-- Subjective Question -->
      <div v-else-if="question.type === 'subjective'">
        <input type="text" placeholder="Type your answer here..." class="text-black rounded-md px-2 my-2" @change="changeAnswer($event, qNum + 1)"> 
      </div>
      <!-- ----- -->

      <!-- True/False Question -->
      <div v-else-if="question.type === 'true_false'">
        <select name="true false" class="rounded-md text-black pl-2 pr-4 my-2" @change="changeAnswer($event, qNum + 1)">
          <option value="default" selected disabled>True/False</option>
          <option value="0"> False </option>
          <option value="1"> True </option>
        </select>
      </div>
      <!-- ----- -->
      
    </div>
  </div>
  <div class="text-lg">
    <button @click="quit" class="px-2">Cancel</button>
    <button @click="submit" class="hover:bg-blue-800 hover:text-white ease-in-out duration-300 rounded-md bg-blue-500 px-2">Submit answers</button>
  </div>
</template>
