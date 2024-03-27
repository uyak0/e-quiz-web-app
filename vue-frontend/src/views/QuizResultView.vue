<script setup>
  import { ref } from 'vue' 
  
  const props = defineProps([
    userAnswers: {
      questionNum: Number,
      answer: String
    },
    quiz: {
      questionNum: Number,
      question: String,
      options: Array,
      correctAnswer: String
    },
  ])
</script>
<template>
  {{ console.log(userAnswers) }}
  {{ console.log(quiz)}}
</template>
