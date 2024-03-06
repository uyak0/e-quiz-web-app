<script setup>
  import TopBar from '@/components/TopBar.vue';
  import UserAvatar from '@/components/UserAvatar.vue';
    
</script>

<template>
  <TopBar />

  <div class="flex justify-center">
    <div class="h-screen bg-slate-700 w-3/4 mx-auto py-8 justify-center">
      <UserAvatar class="w-1/2 h-1/2" /> 
    </div>
  </div>
</template>
