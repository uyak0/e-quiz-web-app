<script setup lang="ts">
import TopBar from '@/components/TopBar.vue'
import VueDatePicker from '@vuepic/vue-datepicker'
import '@vuepic/vue-datepicker/dist/main.css'
import { ref } from 'vue'
import { useRoute } from 'vue-router';

const route = useRoute()

const assignment = ref({
  title: '',
  due_date: new Date(),
  classroom_id: route.params.classroomId
});

function fileHandler() {


}
</script>
<template>
  <TopBar />
  <div class="bg-slate-700 w-3/4 mx-auto py-8 h-screen">
    <!-- Title and Date -->
    <span class="mx-4 font-jetBrains flex flex-rows border-none">
      <input type="text" id="title" name="title" v-model="assignment.title" placeholder="Type Title Here..."
        class="text-ellipsis whitespace-nowrap overflow-hidden text-grey-200 text-5xl bg-transparent ">
      <div class="">
        <VueDatePicker v-model="assignment.due_date" />
      </div>
    </span>

    <div class="rounded-md border-2 mx-4 my-4 py-4 px-4 font-jetBrains">
      <div class="flex flex-col">
        <input type="text" placeholder="Type instructions here..."
          class="placeholder:text-slate-600 bg-transparent w-full mb-4 overflow-visible outline-none border-b-[1px] border-b-slate-300">

        <div class="flex items-center justify-center w-full">
          <label for="dropzone-file"
            class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-transparent dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-500 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
            <div class="flex flex-col items-center justify-center pt-5 pb-6">
              <svg class="w-8 h-8 mb-4 text-gray-400 dark:text-gray-400" aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
              </svg>
              <p class="mb-2 text-sm text-gray-400 dark:text-gray-400"><span class="font-semibold">Click to
                  upload</span> or drag and drop</p>
              <!-- <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF (MAX. 800x400px)</p> -->
            </div>
            <input id="dropzone-file" type="file" class="hidden" @input="fileHandler" @drop="fileHandler" />
          </label>
        </div>

      </div>
    </div>
  </div>
</template>
