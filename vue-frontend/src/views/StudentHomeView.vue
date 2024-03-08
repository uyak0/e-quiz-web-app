<script setup>
  import TopBar from '@/components/TopBar.vue';
  import Classroom from "@/components/Classroom.vue";
  import axios from "axios";
  import {onMounted, ref} from "vue";
  import {useRoute} from "vue-router";

  const API = import.meta.env.VITE_LARAVEL_API
  const route = useRoute()
  const studentId = route.params.id

  const pageName = 'Home'
  const classrooms = ref([])

  async function getClassrooms() {
    await axios.get(API + 'student/classrooms/' + studentId)
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
  <div v-if="classrooms.length === 0" class="flex justify-center">
    <p class="text-2xl">No classrooms found</p>
  </div>

  <div class="w-1/4 flex flex-rows justify-between">
    <div v-for="(item, index) of classrooms" :key="index">
      <Classroom v-model="classrooms[index]"/> 
      {{ console.log(item)}}
    </div>
  </div>
</template>
