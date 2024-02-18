<script setup>
  import '@vuepic/vue-datepicker/dist/main.css'
  import { RouterLink, RouterView } from 'vue-router'
  import TitleInsert from '../../components/Quiz Creation/TitleInsert.vue'
  import SubmitCancel from '../../components/Quiz Creation/SubmitCancel.vue'
</script>

<template>
  <TitleInsert />
  <RouterView />
  <SubmitCancel />
</template>