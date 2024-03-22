<script setup>
  import { ref, watchEffect } from 'vue';

  const modalEnabled = defineModel()
  watchEffect(() => {
    console.log(modalEnabled.value)
  })
</script>
<template>
  <span @click="modalEnabled = !modalEnabled" :class="{ 'fixed flex justify-center z-50 top-0 left-0 h-full w-full bg-black/50': modalEnabled, 'hidden': !modalEnabled }">
    <slot />
  </span>
</template>
