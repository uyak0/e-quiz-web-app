<script setup>
  import { ref, onMounted, watchEffect } from 'vue';
  import axios from 'axios';
  import TopBar from "@/components/TopBar.vue";
  import { useRoute } from 'vue-router'

  const API = import.meta.env.VITE_LARAVEL_API;
  const route = useRoute()

  let quizzes = ref([])
  let userAnswers = ref([])

  async function getQuizzes() {
    const res = await axios.get(API + 'quiz/' + route.params.quizId)
    quizzes.value = res.data
    console.log(quizzes.value)
  }

  function getOptions(options) {
    const separatedOptions = options.split(', ')
    return separatedOptions
  }

  function changeAnswer(event, qNum) {
    const answer = event.target.value
    const existingAnswer = userAnswers.value.find((answer) => answer.questionNum === qNum)
    const index = userAnswers.value.indexOf(existingAnswer)

    if (!existingAnswer) {
      userAnswers.value.push({ questionNum: qNum, answer: answer })
    } else {
      userAnswers.value.splice(index, 1, { questionNum: qNum, answer: answer })
      if (answer == '') {     // for catching empty SUBJECTIVE QUESTION's answers
        userAnswers.value.splice(index, 1,)
      }
    }
  }

  function isDone(qNum) {
    return isDone = this.userAnswers.some(answer => answer.questionNum === qNum);
  }

  function submit() {
    let confirmSubmit;

    console.log(userAnswers.value)

    if (userAnswers.value.length !== quizzes.value.length) {
      alert('Please answer all questions before submitting!')
    } else {
      confirmSubmit = confirm('Are you sure you want to submit your answers?')
    }

    if (confirmSubmit) {
      for (let i = 0; i < userAnswers.value.length; i++) {
        const answer = userAnswers.value[i].answer
        const question = quizzes.value.find((question) => question.id === answer.questionNum)
        if (question.correct_answer === answer.answer || question.correct_answers === answer.answer) {
          console.log('Correct')
        } else {
          console.log('Incorrect')
        }
      }
    }
  }

  onMounted(() => {
    getQuizzes()
  })
</script>

<template>
  <TopBar />
  <div v-for="(question, qNum) in quizzes" :key="qNum"
    class="rounded-md border-2 mx-4 my-4 py-4 px-4 font-jetBrains">
    <!-- Question Component -->
    <div class="bg-transparent">
      <h1 class="text-2xl font-bold" :class="{ 'text-green-400': isDone(qNum + 1) }"> #{{ qNum + 1 }}</h1>
      <p>{{ question.description }}</p>

      <!-- Multi-choice Question -->
      <div v-if="question.options">
        <div v-for="(option, index) in getOptions(question.options)" :key="index">
          <input type="radio" :name="qNum" :value="option" @input="changeAnswer($event, qNum + 1)">
          <label :for="option" class="px-2">
            {{ option }}
          </label>
        </div>
      </div>
      <!-- -------- -->

      <!-- Subjective Question -->
      <div v-else-if="!question.options && typeof(question.correct_answers) === 'string'">
        <input type="text" placeholder="Type your answer here..." class="text-black rounded-md px-2 my-2" @change="changeAnswer($event, qNum + 1)"> 
      </div>
      <!-- ----- -->

      <!-- True/False Question -->
      <div v-else-if="!question.options && typeof(question.correct_answer) === 'number'">
        <select name="true false" class="rounded-md text-black pr-4 my-2" @change="changeAnswer($event, qNum + 1)">
          <option value="default" selected disabled>True/False</option>
          <option value="0"> False </option>
          <option value="1"> True </option>
        </select>
      </div>
      <!-- ----- -->
      
    </div>
  </div>
  <div class="text-lg">
    <button class="px-2">Cancel</button>
    <button @click="submit" class="hover:bg-blue-800 hover:text-white ease-in-out duration-300 rounded-md bg-blue-500 px-2">Submit answers</button>
  </div>
</template>
