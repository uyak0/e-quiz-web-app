<script setup>
  import '@vuepic/vue-datepicker/dist/main.css'
  import { RouterLink, RouterView } from 'vue-router'
  import { ref } from 'vue'
  import TitleDateInsert from '@/components/Quiz Creation/TitleDateInsert.vue'
  import SubmitCancel from '@/components/Quiz Creation/SubmitCancel.vue'
  import MCQuestion from '@/components/Quiz Creation/MCQuestion.vue'

  let selected = ref(null)
  const date = ref('')
</script>

<template>
  <div class="bg-slate-700 h-screen w-3/4 mx-auto">
    <TitleDateInsert :date="date"/>
    <RouterView />
    <SubmitCancel />
        
    <div v-if="selected==='mcq'">
      <MCQuestion />
    </div>

    <!-- Question Type Selection -->
    <select v-model="selected" class="float-right text-slate-600">
      <option value="mcq">Multi-choice</option>
      <option value="sub">Subjective</option>
      <option value="tfq">True/False</option>
    </select>
  </div>

</template>