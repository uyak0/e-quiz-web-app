<template>
  <select @change="$emit('update:questionType', $event.target.value)" class="float-right text-slate-600 pr-4">
    <option value="mcq">Multi-choice</option>
    <option value="sub">Subjective</option>
    <option value="tfq">True/False</option>
  </select>
</template>