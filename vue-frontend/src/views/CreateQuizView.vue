<script setup>
  import '@vuepic/vue-datepicker/dist/main.css'
  import { onMounted, ref } from 'vue'
  import TitleDateInsert from '@/components/Quiz Creation/TitleDateInsert.vue'
  import MCQuestion from '@/components/Quiz Creation/MCQuestion.vue'
  import SelectQType from '@/components/Quiz Creation/SelectQType.vue'
  import SubjectiveQuestion from "@/components/Quiz Creation/SubjectiveQuestion.vue";
  import TFQuestion from "@/components/Quiz Creation/TFQuestion.vue";

  let selected = ref('mcq')   // setting mcq as the default
  const date = ref('')
  const questionProps = ref([{
      questionNo: 1,
      questionType: 'mcq'
  }])

  const questionTypeChange = (questionNo, newValue) => {
    selected.value = newValue;
    questionProps.value.push({ questionNo: questionNo, questionType: newValue }); 
  };

  const addQuestion = () => {
    questionProps.value.questionNo++
  };
  
  onMounted(() => {
    // questionProps.value.push({ questionNo: 2, questionType: 'mcq' });
    console.log(questionProps.value);
  })
</script>

<template>
  <div class="bg-slate-700 h-screen w-3/4 mx-auto ">
    <TitleDateInsert v-model="date"/>

    {{ console.log(questionProps[0]) }}
    <!-- Question Component -->
    <div v-for="index in questionProps.length" :key="index" class="rounded-md border-2 mx-4 my-4 py-4 px-4 font-jetBrains">
      <div class="wrapper grid grid-cols-2 mb-2">
        <h1 class="text-2xl w-1/2">Question #{{ index }}</h1>
        <div class="mx-4">
          <SelectQType v-model="selected" @update:question-type="questionTypeChange(index)"/>
        </div>
      </div>
    
      <!-- Multi-choice Question -->
      <div v-if="selected==='mcq'">
        <MCQuestion />
      </div>

      <!-- Subjective Question -->
      <div v-else-if="selected==='sub'">
        <SubjectiveQuestion />
      </div>

      <!-- True/False Question -->
      <div v-else-if="selected==='tfq'">
        <TFQuestion />
      </div>
    </div>
    <div @click="addQuestion" class="cursor-pointer transition duration-150 ease-in hover:text-sky-950 hover:bg-sky-400 border-dotted border-2 mx-4 my-4 py-4 px-4 rounded-md text-6xl text-center">
      +
    </div>

    <button class="rounded-md bg-blue-500 hover:bg-grey-600 float-right px-2">Create Quiz</button>
    <button class="bg-transparent float-right mx-2">Cancel</button>
  </div>

</template>