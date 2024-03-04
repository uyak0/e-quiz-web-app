<script setup>
  const API = import.meta.env.VITE_LARAVEL_API;

  let quizProps = ref([
  { questionType: }
  ])

  function getQuizzes() {
    axios.get(API + '/api/quizzes')
      .then(response = () => {
        quizProps.value = response.data 
      })
      .catch(error = () => {
        console.log(error) 
      })
  }

  onMounted(() => {
    getQuizzes
  })
</script>

<template>
  <div v-for="(item, index) in quizProps" :key="index">
    <!-- Question Component -->
    <div class="">
      <h1>Question {{ index + 1 }}</h1>
      <div v-if="item.questionType === mcq">
        mcquestion
      </div>

      <div v-if="item.questionType === sub">
        subjective question
      </div>

      <div v-if="item.questionType === tfq">
        true/false
      </div>
    </div>
  </div>
</template>
