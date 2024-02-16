<script setup>
  import VueDatePicker from '@vuepic/vue-datepicker'
  import '@vuepic/vue-datepicker/dist/main.css'
</script>

<template>
  <form class="font-jetBrains flex flex-col">
    <input type="text" id="title" name="title" placeholder="Type Title Here..." class="text-grey-200 text-6xl bg-transparent" />
    <VueDatePicker />     
    <label for="description">Description</label>
    <input type="text" id="description" name="description" placeholder="Description">
  </form>
  
  
  <button class="bg-transparent">Cancel</button>
  <button>Create Quiz</button>
</template>