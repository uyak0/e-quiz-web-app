<script setup>
  import { ref, onMounted } from 'vue';
  import axios from 'axios';
  import TopBar from "@/components/TopBar.vue";

  const API = import.meta.env.VITE_LARAVEL_API;

  let quizProps = ref([
    { questionType: 'mcq' },
    { questionType: 'sub' },
    { questionType: 'tfq' }
  ])

  function getQuizzes() {
    axios.get(API + '/api/quiz')
      .then(response => {
        quizProps.value = response.data
      })
      .catch(error => {
        console.log(error)
      })
  }

  onMounted(() => {
    getQuizzes()
  })
</script>

<template>
  <div v-for="(item, index) in quizProps" :key="index"
    class="rounded-md border-2 mx-4 my-4 py-4 px-4 font-jetBrains">
    <!-- Question Component -->
    <div class="bg-transparent bor">
      <h1>Question #{{ index + 1 }}</h1>
      <div v-if="item.questionType === 'mcq'">
        mcquestion
      </div>

      <div v-if="item.questionType === 'sub'">
        subjective question
      </div>

      <div v-if="item.questionType === 'tfq'">
        true/false
      </div>

    </div>
  </div>
</template>
