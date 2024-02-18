<script setup>
  import '@vuepic/vue-datepicker/dist/main.css'
  import { RouterLink, RouterView } from 'vue-router'
  import { ref } from 'vue'
  import TitleDateInsert from '../../components/Quiz Creation/TitleDateInsert.vue'
  import SubmitCancel from '../../components/Quiz Creation/SubmitCancel.vue'

  const date = ref('')
</script>

<template>
  <div class="bg-slate-700 h-screen w-3/4 mx-auto">
    <TitleDateInsert :date="date"/>
    <RouterView />
    <SubmitCancel />
  </div>
</template>