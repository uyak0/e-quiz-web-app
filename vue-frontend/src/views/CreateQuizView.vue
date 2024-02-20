<script setup>
  import '@vuepic/vue-datepicker/dist/main.css'
  import { ref } from 'vue'
  import TitleDateInsert from '@/components/Quiz Creation/TitleDateInsert.vue'
  import SubmitCancel from '@/components/Quiz Creation/SubmitCancel.vue'
  import MCQuestion from '@/components/Quiz Creation/MCQuestion.vue'
  import SelectQType from '@/components/Quiz Creation/SelectQType.vue'
  import SubjectiveQuestion from "@/components/Quiz Creation/SubjectiveQuestion.vue";
  import TFQuestion from "@/components/Quiz Creation/TFQuestion.vue";

  let selected = ref('mcq')   // setting mcq as the default
  const date = ref('')

  const questionTypeChange = (newValue) => {
    selected.value = newValue;
  };
</script>

<template>
  <div class="bg-slate-700 h-screen w-3/4 mx-auto">
    <TitleDateInsert v-model="date"/>

    <div class="rounded-md border-2 mx-4 my-4 py-4">
      <h3>Question #{{ questionNo }}</h3>
      <div class="mx-4">
        <SelectQType v-model="selected" @update:question-type="questionTypeChange"/>
      </div>
      <!-- Multi-choice Question -->
      <div v-if="selected==='mcq'">
        <MCQuestion />
      </div>

      <!-- Subjective Question -->
      <div v-if="selected==='sub'">
        <SubjectiveQuestion />
      </div>

      <!-- True/False Question -->
      <div v-if="selected==='tfq'">
        <TFQuestion />
      </div>


    </div>

    <SubmitCancel />
  </div>

</template>