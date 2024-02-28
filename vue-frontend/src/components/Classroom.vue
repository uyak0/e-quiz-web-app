<script setup>
  const classroomProps = defineModel({
    name: String,
    desc: String
  })
</script>

<template>
  <div class="rounded-md border-black bg-slate-100 text-black w-20">
    <RouterLink :to="{ path: '/classroom/' + classroomProps.id }">
      {{ console.log($route.params.id)}}
      <div class="banner h-10">
        <img src="https://via.placeholder.com/150" alt="classroom banner" />
      </div>
      <div class="h-10">
        <p>{{ classroomProps.name }}</p>
      </div>
    </RouterLink>
  </div>
</template>
