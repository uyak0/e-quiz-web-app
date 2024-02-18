<script setup>
  import '@vuepic/vue-datepicker/dist/main.css'
  import { RouterLink, RouterView } from 'vue-router'
  import TitleDateInsert from '../../components/Quiz Creation/TitleDateInsert.vue'
  import SubmitCancel from '../../components/Quiz Creation/SubmitCancel.vue'
</script>

<template>
  <div class="bg-slate-700 h-screen w-3/4 mx-auto">
    <TitleDateInsert />
    <RouterView />
    <SubmitCancel />
  </div>
</template>