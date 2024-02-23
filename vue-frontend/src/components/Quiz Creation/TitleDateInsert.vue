<script setup>
  import VueDatePicker from '@vuepic/vue-datepicker'
  import '@vuepic/vue-datepicker/dist/main.css'
  import { ref } from 'vue'

  const date = ref('')
  defineProps(['date'])
</script>

<template>
  <span class="mx-4 font-jetBrains flex flex-rows border-none md:overflow-auto">
    <input type="text" id="title" name="title" placeholder="Type Title Here..." class="text-ellipsis whitespace-nowrap overflow-hidden text-grey-200 text-5xl bg-transparent md:overflow-hidden" />
    <div class="datepicker-wrapper">    
      <VueDatePicker v-model="date" />  
    </div>
  </span>
</template>