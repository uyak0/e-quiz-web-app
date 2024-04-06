<script setup>
  const questionType = defineModel()
</script>

<template>
  <select v-model="questionType" class="rounded-md float-right text-slate-600 pr-4">
    <option value="mcq">Multi-choice</option>
    <option value="sub">Subjective</option>
    <option value="tfq">True/False</option>
  </select>
</template>
