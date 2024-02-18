<script setup>
  import VueDatePicker from '@vuepic/vue-datepicker'
  import '@vuepic/vue-datepicker/dist/main.css'
</script>

<template>
  <span class="mx-4 font-jetBrains flex flex-nowrap">
    <input type="text" id="title" name="title" placeholder="Type Title Here..." class="text-grey-200 text-6xl bg-transparent" />
    <div class="datepicker-wrapper self-end rounded-md w-2 flex-none">    
      <VueDatePicker />  
    </div>
  </span>
</template>