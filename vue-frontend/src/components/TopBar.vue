<script setup>
  import ToggleTheme from './ToggleTheme.vue';
  import UserAvatar from './UserAvatar.vue';
  import { useRoute } from 'vue-router';
  import { onBeforeMount, onMounted, ref, watchEffect } from 'vue';
  import axios from 'axios';

  const API = import.meta.env.VITE_LARAVEL_API

  const route = useRoute()
  const pageName = route.meta.title 
  const userId = route.params.userId
  const userRole = route.params.userRole
  const userName = ref('')
  const studentPoints = ref(0)
  const dailyQuiz = ref({
    quiz_id: 0      // default value so it loads
  })

  const enableButton = defineModel('enableButton')
  const emit = defineEmits(['modalEnabled'])

  async function getUserData() {
    const res = await axios.get(API + 'user/', { params: { id: userId } })
    userName.value = res.data.name 
  }

  async function getPoints() {
    if (userRole === 'student') {
      const res = await axios.get(API + 'student/points', { params: { id: userId } })
      studentPoints.value = res.data;
    }
  }

  async function getDailyQuiz() {
    const res = await axios.get(API + 'quiz/daily')
    if (res.data.daily_quiz) {
      dailyQuiz.value = res.data.daily_quiz
    }
    else return
  }

  onMounted(() => {
    getUserData()
    getPoints()
    getDailyQuiz()
  })
</script>

<template>
  <div class="drop-shadow-md text-black dark:text-slate-400 bg-gray-200 sticky font-jetBrains flex flex-rows text-2xl justify-between px-2 py-1 dark:bg-gray-600 place-items-center">
    <div name="left modules" class="">
      <RouterLink v-if="route.name !== 'quizResult' || route.query.type === 'daily_quiz'" :to="{ name: 'userHome' }" class="dark:hover:text-black hover:text-gray-500"> E-Quizz </RouterLink>
      <RouterLink v-else :to="{ name: 'classroom', params: { classroomId: route.query.classroom } }" class="bg-blue-400 rounded-md px-2 hover:bg-blue-700 hover:text-black"> Back to Classroom </RouterLink>


      <!--Create/join classroom button-->
      <button @click="emit('modalEnabled')" v-if="userRole === 'student' && enableButton"
        class="ml-2 bg-blue-300 hover:bg-blue-600 duration-150 ease-in hover:text-white text-black rounded-md px-2" v-cloak>
        + Join a classroom
      </button>

      <button @click="emit('modalEnabled')" v-else-if="userRole === 'teacher' && enableButton"
        class="ml-2 bg-blue-300 hover:bg-blue-600 duration-150 ease-in hover:text-white text-black rounded-md px-2" v-cloak>
        + Create a classroom
      </button>

      <!-- Daily Quiz -->
      <RouterLink v-if="pageName === 'Home' && dailyQuiz.quiz_id !== 0" :to="{ name: 'quiz', params: { quizId: dailyQuiz.quiz_id }, query: { type: 'daily_quiz' }}" class="bg-purple-500 text-white duration-300 ease-in-out rounded-md px-2 border-b-4 border-b-purple-600 hover:bg-purple-300 hover:border-b-purple-400 hover:text-gray-800 hover:*:animate-none">
        <span class="animate-pulse">
          ! DAILY QUIZ !
        </span>
      </RouterLink>
    </div>

    <!-- Page Name -->
    <div class="hidden sm:block font-firaSans font-bold">
      {{ pageName }}
    </div>

    <div name="theme and user" class="flex flex-row gap-3">
      <div class="place-items-center place-self-center">
        <ToggleTheme /> 
      </div>

      <div>
        <RouterLink :to="{ name: 'Chatroom' }"> 
          <svg class="w-10 h-10 dark:text-white text-black hover:text-gray-500 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a2 2 0 01-2-2V7a2 2 0 012-2h3.586a1 1 0 01.707.293l2.414 2.414A1 1 0 0016.414 8H17z"></path></svg>
        </RouterLink>
      </div>

      <div name="name and role" class="hidden md:block">
        <p class="text-sm font-bold text-right pr-2">{{ userName }}</p>
        <span class="flex flex-row">
          <p class="rounded-md px-2 text-gray-900 float-right text-sm" :class="{ 'bg-red-500': userRole === 'teacher', 'bg-green-500': userRole === 'student' }">{{ userRole }}</p>
          <p v-if="userRole === 'student'" class="text-sm tracking-wide px-2 text-gray-900 h-fit place-self-center bg-pink-300 rounded-md mx-2 text-center">{{ studentPoints }}pts</p>
        </span>
      </div>

      <RouterLink :to="{ name: 'userProfile' }">
        <div class="flex justify-center w-10 h-10 overflow-hidden bg-gray-100 rounded-full dark:bg-gray-600">
          <UserAvatar />
        </div>
      </RouterLink>
    </div>

  </div>
</template>
