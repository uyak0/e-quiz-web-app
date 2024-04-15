<script setup lang="ts">
import TopBar from '@/components/TopBar.vue'
import VueDatePicker from '@vuepic/vue-datepicker'
import '@vuepic/vue-datepicker/dist/main.css'
import { ref } from 'vue'
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';
import { DateTime } from 'luxon'

const API = import.meta.env.VITE_LARAVEL_API

interface UserFiles {
  name: string,
  url: string,
  type: string
}

interface Assignment {
  title: string,
  due_date: DateTime,
  classroom_id: string | string[]
}

const route = useRoute()
const router = useRouter()
const isDragOver = ref<Boolean>(false)
const userFiles = ref<UserFiles[]>([])
const assignment = ref<Assignment>({
  title: '',
  due_date: DateTime.now(),
  classroom_id: route.params.classroomId
})

async function fileHandler(event: Event) {
  const files = (event.target as HTMLInputElement).files
  for (let i = 0; i < files.length; i++) {
    userFiles.value.push({
      name: files[i].name,
      url: URL.createObjectURL(files[i]),
      type: files[i].type
    });
  }
  console.log(userFiles.value)
}

async function dropFileHandler(event: Event) {
  isDragOver.value = false
  const files = (event as DragEvent).dataTransfer?.files
  for (let i = 0; i < files.length; i++) {
    userFiles.value.push({
      name: files[i].name,
      url: URL.createObjectURL(files[i]),
      type: files[i].type
    });
  }
}
</script>
<template>
  <TopBar />
  <div class="text-gray-900 dark:text-darkMode bg-soft-white dark:bg-soft-black">
    <div class="bg-gray-100 dark:bg-slate-700 w-full md:w-3/4 mx-auto py-8 h-screen">
      <!-- Title and Date -->
      <span class="mx-4 font-jetBrains flex border-none justify-between">
        <input type="text" id="title" name="title" v-model="assignment.title" placeholder="Type Title Here..."
          class="truncate text-xl md:text-5xl bg-transparent ">
        <div class="w-fit">
          <VueDatePicker v-model="assignment.due_date" />
        </div>
      </span>

      <div class="rounded-md border-2 mx-4 my-4 py-4 px-4 font-jetBrains">
        <div class="flex flex-col">
          <input type="text" placeholder="Type instructions here..."
            class="placeholder:text-slate-600 bg-transparent w-full mb-4 overflow-visible outline-none border-b-[1px] border-b-slate-300">

          <!-- Attachment List -->
          <div v-if="userFiles.length"> 
            <p class="text-lg font-bold">Attachments</p>
            <div class="flex flex-wrap gap-4 pb-6">
              <a v-for="(file, index) in userFiles" :key="index"
                class="cursor-pointer hover:underline rounded-md border-2 overflow-clip flex items-center justify-center" :href="file.url" target="_blank" draggable="false">
                <!-- Images -->
                <div v-if="file.type.includes('image')">
                  <img draggable="false" :src="file.url" class="cursor-pointer object-cover w-32 h-32" />
                </div>

                <!-- Text/pdf/doc files-->
                <div v-else class="w-32 h-32">
                  <span :src="file.url" class="w-32 h-32"></span>
                </div>

                <!-- Other files -->
                <div v-else class="w-32 h-32">
                  <span :src="file.url" class="w-32 h-32"></span>
                </div>

                <p class="text-xs truncate w-48">{{ file.name }}</p>
                <p v-show=""></p>
              </a>
            </div>
          </div>

          <div class="flex items-center justify-center w-full" :class="{ 'bg-gray-600': isDragOver }">
            <label for="dropzone-file" @dragover.prevent
            @drop.prevent="dropFileHandler($event)" @dragenter="isDragOver = true" @dragleave="isDragOver = false"
              class="*:pointer-events-none flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-transparent dark:hover:bg-bray-800 hover:bg-gray-300 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
              <div class="flex flex-col items-center justify-center pt-5 pb-6">
                <svg class="w-8 h-8 mb-4 text-gray-400 dark:text-gray-400" aria-hidden="true"
                  xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                </svg>
                <p class="mb-2 text-sm text-gray-400 dark:text-gray-400" v-show="isDragOver===false"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                <p class="mb-2 text-sm text-gray-400 dark:text-gray-400" v-show="isDragOver">Drop it like it's hot!</p>
                <!-- <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF (MAX. 800x400px)</p> -->
              </div>
              <input id="dropzone-file" type="file" class="hidden" @input.prevent="fileHandler($event)" multiple>
            </label>
          </div>

        </div>
      </div>
    </div>
  </div>
</template>
