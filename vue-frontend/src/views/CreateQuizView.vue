<script setup>
  import VueDatePicker from '@vuepic/vue-datepicker'
  import '@vuepic/vue-datepicker/dist/main.css'
  import { reactive, onMounted, ref, watchEffect} from 'vue'
  import MCQuestion from '@/components/Quiz Creation/MCQuestion.vue'
  import QuestionTypeDDL from '@/components/Quiz Creation/QuestionTypeDDL.vue'
  import SubjectiveQuestion from "@/components/Quiz Creation/SubjectiveQuestion.vue";
  import TFQuestion from "@/components/Quiz Creation/TFQuestion.vue";
  import { useRoute } from 'vue-router'
  import axios from 'axios'

  const API = import.meta.env.VITE_LARAVEL_API;
  const route = useRoute()

  let questionProps= ref([
    { questionType: 'mcq' }
  ]) // first question defaults

  let quizProps = ref({
    title: '',
    due_date: new Date(),
    questions: questionProps.value,
    classroom_id: route.params.classroomId
  })

  function formatDate(date) {
    const day = date.getDate()
    const month = date.getMonth() + 1
    const year = date.getFullYear()

    const newDate = `${year}-${month}-${day}` 
    const time = date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds()
    return newDate + ' ' + time
  }

  function addQuestion() {
    questionProps.value.push({ questionType: 'mcq' })
  };

  function deleteQuestion(index) {
    questionProps.value.splice(index, 1);
  };

  async function submitQuizCreation() {
    const data = {
      title: quizProps.value.title,
      due_date: formatDate(quizProps.value.due_date),
      classroom_id: route.params.classroomId
    }

    const res = await axios.post(API + 'quiz/create', data)
  }
</script>

<template>
  <div :class="{ 'h-screen': questionProps.length < 3,
                 'h-full': questionProps.length > 4 } " class="bg-slate-700 w-3/4 mx-auto py-8">

    <!-- Title and Date -->
    <span class="mx-4 font-jetBrains flex flex-rows border-none">
      <input type="text" id="title" name="title" v-model="quizProps.title" placeholder="Type Title Here..." class="text-ellipsis whitespace-nowrap overflow-hidden text-grey-200 text-5xl bg-transparent ">
      <div class="">
        <VueDatePicker v-model="quizProps.due_date" />  
      </div>
    </span>

    <!-- Question Component -->
    <div v-for="(item, index) of questionProps" :key="index"
         class="rounded-md border-2 mx-4 my-4 py-4 px-4 font-jetBrains">
      <div class="wrapper flex mb-2">
        <h1 class="text-2xl w-1/2">Question #{{ index + 1 }}</h1>
        <div class="mx-4">
          <QuestionTypeDDL v-model="item.questionType" />
        </div>
        <!-- Delete Button -->
        <div @click="deleteQuestion()" class="text-2xl cursor-pointer hover:text-red-600">
          ⓧ 
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
      <button @click="submitQuizCreation" class="rounded-md bg-blue-500 hover:bg-grey-600 float-right px-2">Create Quiz</button>
      <button class="bg-transparent float-right mx-2">Cancel</button>
    </div>
  </div>

</template>
