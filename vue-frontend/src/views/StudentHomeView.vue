<script setup>
  import TopBar from '@/components/TopBar.vue';
  import Classroom from "@/components/Classroom.vue";
  import axios from "axios";
  import {onMounted, ref} from "vue";

  const pageName = 'Home'
  const classrooms = ref({})
  const API = import.meta.env.VITE_LARAVEL_API

  function getClassrooms() {
    axios.get(API + '/api/classrooms')
      .then(response => {
        classrooms.value = response.data
      })
      .catch(error => {
        console.log(error)
      })
  }
  onMounted(() => {
    getClassrooms();
  })
</script>

<template>
  <TopBar v-model="pageName"/>
  <div v-for="(item, index) in classrooms" :key="index">
    <Classroom v-model="classrooms[index]"/>
  </div>
</template>
