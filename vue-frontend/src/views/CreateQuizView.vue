<script setup>
  import '@vuepic/vue-datepicker/dist/main.css'
  import { onMounted, ref } from 'vue'
  import TitleDateInsert from '@/components/Quiz Creation/TitleDateInsert.vue'
  import MCQuestion from '@/components/Quiz Creation/MCQuestion.vue'
  import SelectQType from '@/components/Quiz Creation/SelectQType.vue'
  import SubjectiveQuestion from "@/components/Quiz Creation/SubjectiveQuestion.vue";
  import TFQuestion from "@/components/Quiz Creation/TFQuestion.vue";

  const date = ref('')
  let questionProps = ref([{
      questionNo: 1,
      questionType: 'mcq'
  }]) // first question defaults

  const questionTypeChange = (questionNo, newValue) => {
    const index = questionProps.value.findIndex(obj => obj.questionNo === questionNo);    // find index of object with the same question number
    questionProps.value[index].questionType = newValue;     // use the index to update questionType to the new one
  };

  const addQuestion = () => {
    questionProps.value.push({ questionNo: questionProps.value.length+1, questionType: 'mcq' })
  };
  
  onMounted(() => {
    // questionProps.value.push({ questionNo: 2, questionType: 'mcq' });
    console.log(questionProps.value);
  })
</script>

<template>
  <div class="bg-slate-700 h-screen w-3/4 mx-auto ">
    <TitleDateInsert v-model="date"/>

    <!-- Question Component -->
    <div v-for="index of questionProps.length" :key="index"
         class="rounded-md border-2 mx-4 my-4 py-4 px-4 font-jetBrains">
      <div class="wrapper grid grid-cols-2 mb-2">
        <h1 class="text-2xl w-1/2">Question #{{ index }}</h1>
        <div class="mx-4">
          <!-- sorry to anyone that has to read this, idk a better way to do this -->
          <SelectQType v-model="questionProps[index-1].questionType"
                       @update:question-type="questionTypeChange(questionProps[index-1].questionNo, $event)"/>
        </div>
      </div>
    
      <!-- Multi-choice Question -->
      <div v-if="questionProps[index-1].questionType==='mcq'">
        <MCQuestion />
      </div>

      <!-- Subjective Question -->
      <div v-else-if="questionProps[index-1].questionType==='sub'">
        <SubjectiveQuestion />
      </div>

      <!-- True/False Question -->
      <div v-else-if="questionProps[index-1].questionType==='tfq'">
        <TFQuestion />
      </div>
    </div>

    <!-- Add Question button -->
    <div @click="addQuestion" class="cursor-pointer transition duration-150 ease-in hover:text-sky-950 hover:bg-sky-400 border-dotted border-2 mx-4 my-4 py-4 px-4 rounded-md text-6xl text-center">
      +
    </div>

    <button class="rounded-md bg-blue-500 hover:bg-grey-600 float-right px-2">Create Quiz</button>
    <button class="bg-transparent float-right mx-2">Cancel</button>
  </div>

</template>