<script setup>
  const props = defineProps({
    selected: String
  })

  props.selected = {
    
  }
</script>

<template>
  <select class="float-right text-slate-600">
    <option value="{{ props.selected }}">Multi-choice</option>
    <option value="{{ props.selected }}">Subjective</option>
    <option value="{{ props.selected }}">True/False</option>
  </select>
</template>