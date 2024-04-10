<script setup>
  import { ref, onMounted } from "vue";

  const correctAnswer = ref('')
  const isCaseSensitive = ref(false)

  const emits = defineEmits([ 
    'addAnswer', 
    'caseSensitive',
  ]);

  const props = defineProps([
    'questionNum',
  ])
</script>

<template>
  <div class="flex flex-rows gap-10">
    <input type="text" v-model="correctAnswer" @change="$emit('addAnswer', correctAnswer); $emit('caseSensitive', isCaseSensitive)" placeholder="Type the correct answer here" class="bg-gray-500 rounded-md px-2 w-1/2">
    <span class="flex gap-2">
      <input type="checkbox" :id="questionNum" @change="$emit('caseSensitive', isCaseSensitive )" v-model="isCaseSensitive">
      <label :for="questionNum">Case sensitive?</label>
    </span>
  </div>
</template> 
