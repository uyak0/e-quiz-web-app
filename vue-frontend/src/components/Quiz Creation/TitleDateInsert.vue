<script setup>
  import VueDatePicker from '@vuepic/vue-datepicker'
  import '@vuepic/vue-datepicker/dist/main.css'
  import { ref } from 'vue'

  const date = ref('')
  defineProps([ 'date' ])
</script>

<template>
  <span class="mx-4 font-jetBrains flex flex-nowrap border-none">
    <input type="text" id="title" name="title" placeholder="Type Title Here..." class="text-grey-200 text-6xl bg-transparent" />
    <div class="datepicker-wrapper self-end rounded-md flex-none">    
      <VueDatePicker v-model="date" />  
    </div>
  </span>
</template>