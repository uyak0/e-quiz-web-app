<script setup lang="ts">
import TopBar from '@/components/TopBar.vue'
import VueDatePicker from '@vuepic/vue-datepicker'
import '@vuepic/vue-datepicker/dist/main.css'
import VueFeather from 'vue-feather'
import { ref } from 'vue'
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';
import { DateTime } from 'luxon'

const API = import.meta.env.VITE_LARAVEL_API

interface UserFiles {
  name: string,
  url: string,
  type: string,
  file: File
}

const route = useRoute()
const router = useRouter()
const isDragOver = ref<Boolean>(false)
const userFiles = ref<UserFiles[]>([])
const assignment = ref({
  title: '',
  description: '',
  due_date: new Date(),
  classroom_id: route.params.classroomId
})

async function fileHandler(event: Event) {
  const files = (event.target as HTMLInputElement).files
  for (let i = 0; i < files.length; i++) {
    userFiles.value.push({
      name: files[i].name,
      url: URL.createObjectURL(files[i]),
      type: files[i].type,
      file: files[i]
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
      type: files[i].type,
      file: files[i]
    });
  }
}

async function createAssignment() {
  try {

    let formData = new FormData();

    formData.append('title', assignment.value.title);
    formData.append('description', assignment.value.description);
    formData.append('due_date', DateTime.fromISO(assignment.value.due_date.toISOString()));
    formData.append('classroom_id', route.params.classroomId);

    // If userFiles.value is an array of File objects
    for (let i = 0; i < userFiles.value.length; i++) {
        formData.append('files[]', userFiles.value[i].file);
    }

    const response = await axios.post(`${API}assignment/create`,formData , {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })
    console.log(response.data)
  } catch (error) {
    console.error(error)
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
          <input v-model="assignment.description" type="text" placeholder="Type instructions here..."
            class="placeholder:text-slate-600 bg-transparent w-full mb-4 overflow-visible outline-none border-b-[1px] border-b-slate-300">

          <!-- Attachment List -->
          <div v-if="userFiles.length">
            <p class="text-lg font-bold">Attachments</p>
            <div class="flex flex-wrap gap-4 pb-6">
              <a v-for="(file, index) in userFiles" :key="index"
                class="*:h-16 cursor-pointer hover:underline rounded-md border-2 overflow-clip flex items-center"
                :href="file.url" target="_blank" draggable="false">
                <div class="w-16 *:h-full">
                  <!-- Images -->
                  <div v-if="file.type.includes('image')">
                    <img draggable="false" :src="file.url" class="h-full cursor-pointer object-cover" />
                  </div>

                  <!-- Text/pdf/doc files-->
                  <div v-else-if="file.type.includes('text')" class="flex justify-center items-center">
                    <vue-feather type="file-text" class="w-14 dark:text-gray-100"></vue-feather>
                  </div>

                  <!-- Other files -->
                  <div v-else class="justify-center flex items-center">
                    <vue-feather type="file" class="w-14 dark:text-gray-100"></vue-feather>
                  </div>
                </div>

                <p class="w-32 flex items-center p-2 overflow-y-scroll text-xs align-center break-all">{{ file.name }}</p>
              </a>
            </div>
          </div>

          <!-- Dropzone -->
          <div class="flex items-center justify-center w-full" :class="{ 'bg-gray-600': isDragOver }">
            <label for="dropzone-file" @dragover.prevent @drop.prevent="dropFileHandler($event)"
              @dragenter="isDragOver = true" @dragleave="isDragOver = false"
              class="*:pointer-events-none flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-transparent dark:hover:bg-bray-800 hover:bg-gray-300 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
              <div class="flex flex-col items-center justify-center pt-5 pb-6">
                <svg class="w-8 h-8 mb-4 text-gray-400 dark:text-gray-400" aria-hidden="true"
                  xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                </svg>
                <p class="mb-2 text-sm text-gray-400 dark:text-gray-400" v-show="isDragOver === false"><span
                    class="font-semibold">Click to upload</span> or drag and drop</p>
                <p class="mb-2 text-sm text-gray-400 dark:text-gray-400" v-show="isDragOver">Drop it like it's hot!</p>
                <!-- <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF (MAX. 800x400px)</p> -->
              </div>
              <input id="dropzone-file" type="file" class="hidden" @input.prevent="fileHandler($event)" multiple>
            </label>
          </div>

          <!-- Submit Button -->
          <button @click="createAssignment"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4">
            Create Assignment
          </button>


        </div>
      </div>
    </div>
  </div>
</template>
