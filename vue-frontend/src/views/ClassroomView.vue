<script setup>
  import axios from "axios";
  import {useRoute} from "vue-router";
  import {onMounted, ref} from "vue";

  const route = useRoute()
  const API = import.meta.env.VITE_LARAVEL_API
  const classroom = ref({});

  function getClassroom() {
    axios.get(API + '/api/classrooms/' + route.params.id)
        .then(response => {
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
  <div class="banner">
    <img src="https://via.placeholder.com/200" alt="banner" class="w-full h-96 object-cover">
  </div>
  <h1 class="text-9xl">{{classroom.name}}</h1>
  <h3 class="text-4xl">{{classroom.description}}</h3>
</template>