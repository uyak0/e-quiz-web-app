<script setup>
  import { ref, onMounted } from 'vue';
  import axios from 'axios';
  import TopBar from "@/components/TopBar.vue";
  import { useRoute } from 'vue-router'

  const API = import.meta.env.VITE_LARAVEL_API;
  const route = useRoute()

  let quizProps = ref([])

  function getQuizzes() {
    axios.get(API + 'quiz/' + route.params.quizId)
      .then(response => {
        quizProps.value = response.data
      })
      .catch(error => {
        console.log(error)
      })
  }

  function getOptions(options) {
    const separatedOptions = options.split(', ')
    return separatedOptions
  }

  onMounted(() => {
    getQuizzes()
  })
</script>

<template>
  <div v-for="(item, index) in quizProps" :key="index"
    class="rounded-md border-2 mx-4 my-4 py-4 px-4 font-jetBrains">
    <!-- Question Component -->
    <div class="bg-transparent">
      <h1 class="text-2xl font-bold"> #{{ index + 1 }}</h1>
      <p>{{ item.description }}</p>

      <div v-if="item.options">
        <div v-for="(options, index) in getOptions(item.options)" :key="index">
          <input type="radio" name="options">
          <label for="options" class="px-2">
            {{ options }}
          </label>
        </div>
      </div>

      <div v-else-if="!item.options && typeof(item.correct_answers) === 'string'">
        <input type="text" placeholder="Type your answer here..." class="text-black rounded-md px-2 my-2"> 
      </div>

      <div v-else-if="!item.options && typeof(item.correct_answer) === 'number'">
        <select name="true false" class="rounded-md text-black pr-4 my-2">
          <option value="0"> False </option>
          <option value="1"> True </option>
        </select>
      </div>
      
    </div>
  </div>
  <div>
    <button>Cancel</button>
    <button>Submit answers</button>
  </div>
</template>
