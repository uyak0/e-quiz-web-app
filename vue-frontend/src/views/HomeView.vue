<script setup>
import TheWelcome from '../components/TheWelcome.vue'
import axios from 'axios'
import { onMounted, reactive } from 'vue';

const state = reactive ({
  students: [],
})

async function getData() {
  await axios.get('http://localhost:8000/api/students')
  .then(response => {
    console.log(response.data)
    state.students = response.data
  })
  .catch(error => {
    console.log(error)
  });
}

onMounted(getData)
</script>

<template>
  <main>
      <div>
        this is home page
      </div>
  </main>
</template>
