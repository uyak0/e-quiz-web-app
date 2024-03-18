<script setup>
  import ToggleTheme from './ToggleTheme.vue';
  import UserAvatar from './UserAvatar.vue';
  import { useRoute } from 'vue-router';
  import { onMounted, ref } from 'vue';

  const route = useRoute()
  const pageName = route.meta.title 
  const userId = route.params.userId
  const userRole = route.params.userRole

  const userProfileLink = '/' + userRole + '/' + userId + '/profile'
  const userHomeLink = '/' + userRole + '/' + userId + '/home'
  const joinClassroomLink = '/classroom/join'
</script>

<template>
  <div class="sticky font-jetBrains flex flex-rows text-2xl justify-between px-2 py-1 bg-gray-600 place-items-center">
    <RouterLink :to="userHomeLink" class="justify-self-center"> E-Quizz </RouterLink>

    <!-- Page Name -->
    <div class="hidden sm:inline font-firaSans font-bold">
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
