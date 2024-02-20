<script setup>
  import '@vuepic/vue-datepicker/dist/main.css'
  import { RouterLink, RouterView } from 'vue-router'
  import { ref } from 'vue'
  import TitleDateInsert from '@/components/Quiz Creation/TitleDateInsert.vue'
  import SubmitCancel from '@/components/Quiz Creation/SubmitCancel.vue'
  import MCQuestion from '@/components/Quiz Creation/MCQuestion.vue'
  import SelectQType from '@/components/Quiz Creation/SelectQType.vue'

  let selected = ref(null)
  const date = ref('')
</script>

<template>
  <div class="bg-slate-700 h-screen w-3/4 mx-auto">
    <TitleDateInsert :date="date"/>
    <RouterView />
    <SubmitCancel />
        
    <div v-if="selected==='mcq'" class="rounded-md border-2 mx-4 my-4">
      <MCQuestion />
    </div>

    <!-- Question Type Selection -->
    <SelectQType v-model="selected"/>
  </div>

</template>