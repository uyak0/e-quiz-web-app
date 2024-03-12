<script setup>
  import TopBar from "@/components/TopBar.vue";
  import axios from "axios";
  import {useRoute} from "vue-router";
  import {onMounted, ref} from "vue";

  const route = useRoute()
  const API = import.meta.env.VITE_LARAVEL_API
  const classroom = ref({});

  const userRole = localStorage.getItem('user_role')

  async function getClassroom() {
    await axios.get(API + 'classroom/' + route.params.id)
      .then(response => {
        console.log(response.data);
        classroom.value = response.data;
      })
      .catch(error => {
        console.log(error);
      });
  }
  
  onMounted(() => {
    getClassroom();
  })

</script>

<template>
  <TopBar />
  <div class="banner">
    <img src="https://via.placeholder.com/200" alt="banner" class="w-full h-96 object-cover">
  </div>
  <div class="flex flex-rows flex-grow-0">
    <div class="flex flex-col">
      <h1 class="text-9xl">{{classroom.name}}</h1>
      <h3 class="text-4xl">{{classroom.description}}</h3>
    </div>
    <RouterLink v-if="userRole === 'teacher'" to="quiz/create" class="bg-blue-400 hover:bg-blue-600 hover:text-black rounded-md text-2xl px-2"> 
      + Create a Quiz 
    </RouterLink>
  </div>
</template>
