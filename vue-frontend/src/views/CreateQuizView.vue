<script setup>
  import '@vuepic/vue-datepicker/dist/main.css'
  import { onMounted, ref } from 'vue'
  import TitleDateInsert from '@/components/Quiz Creation/TitleDateInsert.vue'
  import MCQuestion from '@/components/Quiz Creation/MCQuestion.vue'
  import QuestionTypeDDL from '@/components/Quiz Creation/QuestionTypeDDL.vue'
  import SubjectiveQuestion from "@/components/Quiz Creation/SubjectiveQuestion.vue";
  import TFQuestion from "@/components/Quiz Creation/TFQuestion.vue";

  const date = ref('')
  let questionProps= ref([
      { questionType: 'mcq' }
  ]) // first question defaults

  const addQuestion = () => {
    questionProps.value.push({ questionNo: questionProps.value.length+1, questionType: 'mcq' })
  };
</script>

<template>
  <div class="bg-slate-700 h-full w-3/4 mx-auto py-8">
    <TitleDateInsert v-model="date"/>

    <!-- Question Component -->
    <div v-for="(item, index) of questionProps" :key="index"
         class="rounded-md border-2 mx-4 my-4 py-4 px-4 font-jetBrains">
      <div class="wrapper grid grid-cols-2 mb-2">
        <h1 class="text-2xl w-1/2">Question #{{ index + 1 }}</h1>
        <div class="mx-4">
          <!-- sorry to anyone that has to read this, idk a better way to do this -->
          <QuestionTypeDDL v-model="item.questionType" />
        </div>
      </div>
    
      <!-- Multi-choice Question -->
      <div v-if="item.questionType==='mcq'">
        <MCQuestion />
      </div>

      <!-- Subjective Question -->
      <div v-else-if="item.questionType==='sub'">
        <SubjectiveQuestion />
      </div>

      <!-- True/False Question -->
      <div v-else-if="item.questionType==='tfq'">
        <TFQuestion />
      </div>
    </div>

    <!-- Add Question button -->
    <div @click="addQuestion" class="cursor-pointer transition duration-150 ease-in hover:text-sky-950 hover:bg-sky-400 border-dotted border-2 mx-4 my-4 py-4 px-4 rounded-md text-6xl text-center">
      +
    </div>

    <!-- Submit and Cancel buttons -->
    <div class="mx-4 text-md">
      <button class="rounded-md bg-blue-500 hover:bg-grey-600 float-right px-2">Create Quiz</button>
      <button class="bg-transparent float-right mx-2">Cancel</button>
    </div>
  </div>

</template>