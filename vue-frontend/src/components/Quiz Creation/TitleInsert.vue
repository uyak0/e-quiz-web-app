<script setup>
  import VueDatePicker from '@vuepic/vue-datepicker'
  import '@vuepic/vue-datepicker/dist/main.css'
</script>

<template>
  <span class="font-jetBrains flex-row flex">
    <input type="text" id="title" name="title" placeholder="Type Title Here..." class="text-grey-200 text-6xl bg-transparent flex" />
    <VueDatePicker class="" />  
  </span>
</template>