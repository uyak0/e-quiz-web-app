<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRoute, useRouter } from 'vue-router';
import { DateTime } from 'luxon'
import VueDatePicker from '@vuepic/vue-datepicker'
import '@vuepic/vue-datepicker/dist/main.css'

const API = import.meta.env.VITE_LARAVEL_API
const route = useRoute()
const router = useRouter()
const assignmentId = route.params.assignmentId;

const assignment = ref({
  title: 'Loading...',
  description: 'Loading...',
  due_date: 'Loading...',
  files: []
});

onMounted(async () => {
  try {
    const response = await axios.get(API + 'assignment/show/' + route.params.assignmentId);
    assignment.value = response.data;
    // Modify the file structure to include the original file name
    assignment.value.files = assignment.value.files.map(file => ({
      url: file.url,
      originalName: file.original_name
    }));
  } catch (error) {
    console.error(error);
    assignment.value = {
      title: 'Failed to load',
      description: 'Failed to load',
      due_date: 'Failed to load',
      files: []
    };
  }
});
</script>

<template>
  <div class="flex flex-col md:flex-row p-4 gap-4">
    <!-- Left Section: Assignment Details -->
    <div class="flex-1">
      <div class="bg-white shadow-md rounded-lg p-4">
        <h2 class="text-2xl font-bold mb-2 text-black">{{ assignment.title }}</h2>
        <p class="text-gray-700">{{ assignment.description }}</p>
        <p class="text-gray-600">Due date: {{ new Date(assignment.due_date).toLocaleString() }}</p>
        <div class="mt-4">
          <h2 class="font-semibold mb-2">Attached Files:</h2>
          <ul>
            <li v-for="(file, index) in assignment.files" :key="index">
              <a :href="file.url" target="_blank" class="text-blue-500 hover:underline">{{ file.originalName }}</a>
            </li> 
          </ul>
        </div>
      </div>
      <!-- Comment Section -->
      <div class="mt-4 bg-white shadow-md rounded-lg p-4">
        <h3 class="font-semibold mb-2">Comments</h3>
        <!-- Comments will be added here -->
      </div>
    </div>

    <!-- Right Section: Submission Part -->
    <div class="w-full md:w-1/3">
      <div class="bg-white shadow-md rounded-lg p-4">
        <h3 class="font-semibold mb-2">Submit Your Work</h3>
        <input type="file" @change="handleFileUpload" class="block w-full text-sm text-gray-500
          file:mr-4 file:py-2 file:px-4
          file:rounded-full file:border-0
          file:text-sm file:font-semibold
          file:bg-blue-50 file:text-blue-700
          hover:file:bg-blue-100
        "/>
        <button @click="submitAssignment" class="mt-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
          Submit
        </button>
      </div>
    </div>
  </div>
</template>
