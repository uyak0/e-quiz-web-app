<script setup>
  import { ref, watchEffect } from 'vue';

  const modalEnabled = defineModel()
  function closeModal() { modalEnabled.value = !modalEnabled.value }
</script>
<template>
  <span @click.self="closeModal" @keyup.esc="closeModal" :class="{ 'fixed flex justify-center z-10 top-0 left-0 h-full w-full bg-black/50': modalEnabled, 'hidden': !modalEnabled }">
    <slot />
  </span>
</template>
