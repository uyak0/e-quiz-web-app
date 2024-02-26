<script setup>
  import axios from "axios";

  const classroomProps = defineModel({
    name: String,
    desc: String
  })
</script>

<template>
  <div>
    <p>{{ classroomProps.name }}</p>
  </div>
</template>
