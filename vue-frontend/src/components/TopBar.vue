<script setup>
  import ToggleTheme from './ToggleTheme.vue';
  import UserAvatar from './UserAvatar.vue';
  import { useRoute } from 'vue-router';
  import { onMounted, ref } from 'vue';

  const route = useRoute()
  const pageName = route.meta.title 

  const userRole = localStorage.getItem('user_role')
  const userId = localStorage.getItem('user_id')
  const token = localStorage.getItem('token')

  const userProfileLink = '/user/' + userId + '/profile'
  const userHomeLink = '/' + userRole + '/' + userId + '/home'
</script>

<template>
  <div class="sticky font-jetBrains flex flex-rows text-2xl justify-between px-2 py-1 bg-gray-600 place-items-center">
    <div name="left modules" class="">
      <RouterLink v-if="!token" to="/"> E-Quizz </RouterLink>
      <RouterLink v-else-if="token" :to="userHomeLink"> E-Quizz </RouterLink>

      <RouterLink v-if="userRole === 'student' && pageName.includes('Home')" to="classroom/join" class="ml-2 bg-blue-300 hover:bg-blue-600 duration-150 ease-in hover:text-white text-black rounded-md px-2"> 
        + Join a classroom 
      </RouterLink>
    </div>

    <!-- Page Name -->
    <div class="font-firaSans font-bold">
      {{ pageName }}
    </div>
    
    <div name="theme and user" class="flex flex-row gap-10">
      <div class="place-items-center place-self-center">
        <ToggleTheme @dark-toggle="darkToggle"/> 
      </div>

      <RouterLink :to="userProfileLink">
        <div class="flex justify-center w-10 h-10 overflow-hidden bg-gray-100 rounded-full dark:bg-gray-600">
          <UserAvatar />
        </div>
      </RouterLink>
    </div>

  </div>
</template>
