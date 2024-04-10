<script setup>
  import VueDatePicker from '@vuepic/vue-datepicker'
  import '@vuepic/vue-datepicker/dist/main.css'
  import { reactive, onMounted, ref, watchEffect } from 'vue'
  import MCQuestion from '@/components/Quiz Creation/MCQuestion.vue'
  import QuestionTypeDDL from '@/components/Quiz Creation/QuestionTypeDDL.vue'
  import SubjectiveQuestion from "@/components/Quiz Creation/SubjectiveQuestion.vue";
  import TFQuestion from "@/components/Quiz Creation/TFQuestion.vue";
  import { useRoute, useRouter } from 'vue-router'
  import axios from 'axios'
  import VueFeather from 'vue-feather'

  const API = import.meta.env.VITE_LARAVEL_API;
  const route = useRoute()
  const router = useRouter()

  let questionProps= ref([
    { 
      questionType: 'mcq',
      questionDesc: '',
    }
  ]) // first question defaults

  let quizProps = ref({
    title: '',
    due_date: new Date(),
    questions: questionProps.value,
    classroom_id: route.params.classroomId
  })

  function addCorrectAnswer(index, correctAnswers) {
    if (questionProps.value[index].questionType === 'mcq') {
      questionProps.value[index].correctAnswers = correctAnswers
    }
    else {
      delete questionProps.value[index].correctAnswers
      questionProps.value[index].correctAnswer = correctAnswers
    }
    console.log(questionProps.value)
  }

  function isCaseSensitive(index, value) {
    questionProps.value[index].caseSensitive = value 
    console.log(questionProps.value)
  }

  function addOptions(index, options) {
    questionProps.value[index].options = options
    console.log(questionProps.value)
  }
  
  function changeDesc(index, desc) {
    questionProps.value[index].questionDesc = desc
    console.log(questionProps.value)
  }

  function addQuestion() {
    questionProps.value.push({ questionType: 'mcq' })
  };

  function deleteQuestion(index) {
    questionProps.value.splice(index, 1);
  };

  function quit() {
    let res = confirm('Are you sure you want to quit quiz creation? Any unsaved changes will be lost!')
    if (res) router.push({ name: 'classroom' })
  }

  async function submitQuizCreation() {
    if (quizProps.value.title === '') {
      alert('Please enter a title for the quiz')
      return
    }

    if (quizProps.value.due_date < Date.now()){
      alert('Due date cannot be earlier than the current date!')
    }

    if (questionProps.value.length < 1) {
      alert('Please add at least one question to the quiz')
      return
    }

    const data = {
      title: quizProps.value.title,
      due_date: quizProps.value.due_date.toISOString().slice(0, 19).replace('T', ' '),
      classroom_id: Number(route.params.classroomId),
      questions: questionProps.value
    }

    const res = await axios.post(API + 'quiz/create', data)
    if (res.data.status === 'success') {
      router.push('/teacher/' + route.params.userId + '/classroom/' + route.params.classroomId)
    }
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
      <div class="wrapper flex flex-row mb-2 justify-between">
        <h1 class="text-2xl w-1/2">Question #{{ index + 1 }}</h1>
        <div class="mx-4">
          <QuestionTypeDDL v-model="item.questionType" />
        </div>
        <!-- Delete Button -->
        <vue-feather @click="deleteQuestion()" class="text-2xl cursor-pointer hover:text-red-600" type="x-circle" />
      </div>
      <!-- Question Description -->
      <input type="text" v-model="item.questionDesc" placeholder="Type Question here..." class="placeholder:text-slate-600 bg-transparent w-full mb-4 overflow-visible outline-none border-b-[1px] border-b-slate-300">
    
      <!-- Multi-choice Question -->
      <div v-if="item.questionType==='mcq'">
        <MCQuestion @add-answer="(answers) => addCorrectAnswer(index, answers)" @add-option="(options) => addOptions(index, options)" @add-desc="(desc) => changeDesc(index, desc)"/>
      </div>

      <!-- Subjective Question -->
      <div v-else-if="item.questionType==='sub'">
        <SubjectiveQuestion :question-num="index" @add-answer="(answer) => addCorrectAnswer(index, answer)" @case-sensitive="(value) => isCaseSensitive(index, value)" />
      </div>

      <!-- True/False Question -->
      <div v-else-if="item.questionType==='tfq'">
        <TFQuestion @change-answer="(answer) => addCorrectAnswer(index, answer)"/>
      </div>
    </div>

    <!-- Add Question button -->
    <div @click="addQuestion" class="cursor-pointer transition duration-150 ease-in hover:text-sky-950 hover:bg-sky-400 border-dotted border-2 mx-4 my-4 py-4 px-4 rounded-md text-6xl text-center">
      +
    </div>

    <!-- Submit and Cancel buttons -->
    <div class="mx-4 text-md">
      <button @click="submitQuizCreation" class="disabled:bg-blue-800 rounded-md bg-blue-500 hover:bg-grey-600 float-right px-2">Create Quiz</button>
      <button @click="quit" class="bg-transparent float-right mx-2">Cancel</button>
    </div>
  </div>

</template>
