<script setup>
  import ToggleTheme from './ToggleTheme.vue';
  import UserAvatar from './UserAvatar.vue';
  import Modal from './Modal.vue';
  import { useRoute } from 'vue-router';
  import { onMounted, ref, watchEffect } from 'vue';
  import axios from 'axios';

  const API = import.meta.env.VITE_LARAVEL_API

  const route = useRoute()
  const pageName = route.meta.title 
  const userId = route.params.userId
  const userRole = route.params.userRole

  const enableButton = defineModel('enableButton')
  const emit = defineEmits(['modalEnabled'])

  const classroomCode = ref('')
  const classroomName = ref('');
  const classroomDesc = ref('');

  function emitModalEnable() {
    emit('modalEnabled', true)
  }

  onMounted(() => {
    console.log(route.meta)
  })
</script>

<template>
  <div class="sticky font-jetBrains flex flex-rows text-2xl justify-between px-2 py-1 bg-gray-600 place-items-center">
    <div name="left modules" class="">
      <RouterLink :to="{ name: 'userHome' }"> E-Quizz </RouterLink>

      <!--Create/join classroom button-->
      <button @click="emitModalEnable" v-if="userRole === 'student' && enableButton" class="ml-2 bg-blue-300 hover:bg-blue-600 duration-150 ease-in hover:text-white text-black rounded-md px-2">
        + Join a classroom 
      </button>

      <button @click="emitModalEnable" v-else-if="userRole === 'teacher' && enableButton" class="ml-2 bg-blue-300 hover:bg-blue-600 duration-150 ease-in hover:text-white text-black rounded-md px-2"> 
        + Create a classroom
      </button>
    </div>

    <!-- Page Name -->
    <div class="hidden sm:block font-firaSans font-bold">
      {{ pageName }}
    </div>
    
    <div name="theme and user" class="flex flex-row gap-10">
      <div class="place-items-center place-self-center">
        <ToggleTheme @dark-toggle="darkToggle"/> 
      </div>

      <RouterLink :to="{ name: 'userProfile' }">
        <div class="flex justify-center w-10 h-10 overflow-hidden bg-gray-100 rounded-full dark:bg-gray-600">
          <UserAvatar />
        </div>
      </RouterLink>
    </div>

  </div>
</template>
