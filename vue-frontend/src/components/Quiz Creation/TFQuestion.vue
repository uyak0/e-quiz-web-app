<script setup>
  import { ref, onMounted } from "vue";

  const correctAnswer = ref('default')
  const emits = defineEmits([ 
    'changeAnswer',
  ]);

</script>
<template>
  <div class="mx-4 flex flex-col">
    <select @change="$emit('changeAnswer', Number(correctAnswer))" v-model="correctAnswer" class="float-right text-slate-600 my-2 w-1/4 rounded-md">
      <option value="default" disabled>True/False</option>
      <option value="1">True</option>
      <option value="0">False</option>
    </select>
  </div>
</template>
