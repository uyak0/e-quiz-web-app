<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRoute, useRouter } from 'vue-router';
import Modal from '@/components/Modal.vue';

const API = import.meta.env.VITE_LARAVEL_API;
const route = useRoute();
const router = useRouter();
const assignmentId = route.params.assignmentId;
const userRole = route.params.userRole
const gradeSubmissionModal = ref(false)


const assignment = ref({
  title: 'Loading...',
  description: 'Loading...',
  due_date: 'Loading...',
  files: []
});

const comments = ref([]);
const newComment = ref('');

const fetchComments = async () => {
  const response = await axios.get(`${API}assignment/${assignmentId}/comments`);
  comments.value = response.data;
};

const submitComment = async () => {
  await axios.post(`${API}assignment/${assignmentId}/comments`, { content: newComment.value });
  newComment.value = ''; // Clear the textarea
  fetchComments(); // Refresh the comments list
};

const selectedFiles = ref([]); // To store the selected file

const handleFileUpload = (event) => {
  for (let file of event.target.files) {
    const fileData = {
      file: file,
      url: URL.createObjectURL(file) // Generate a URL for previewing the file
    };
    selectedFiles.value.push(fileData); // Add new file to the list
  }
};

const submitAssignment = async () => {
  if (selectedFiles.value.length === 0) {
    alert('Please select files to submit.');
    return;
  }

  const formData = new FormData();
  selectedFiles.value.forEach((fileData, index) => {
    formData.append(`files[${index}]`, fileData.file); // Append each file to formData with an array-like key
  });

  try {
    const response = await axios.post(`${API}assignment/${assignmentId}/submit`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    });
    alert('Assignment submitted successfully.');
    selectedFiles.value = []; // Optionally clear the selected files after successful submission
  } catch (error) {
    console.error('Error submitting assignment:', error);
    alert('Failed to submit assignment.');
  }
};

const submissions = ref([]);
var clickedSubmission = ref([]);

const fetchSubmissionDetails = async () => {
  try {
    const response = await axios.get(`${API}assignment/${assignmentId}/submissions`);
    submissions.value = response.data;
  } catch (error) {
    console.error('Error fetching submission details:', error);
  }
};

async function openGradeSubmissionModal(clickedSub){
  gradeSubmissionModal.value = true
  clickedSubmission = clickedSub;
}


onMounted(async () => {
  try {
    const response = await axios.get(`${API}assignment/show/${assignmentId}`);
    assignment.value = response.data;
    // Modify the file structure to include the original file name
    assignment.value.files = assignment.value.files.map(file => ({
      url: file.url,
      originalName: file.original_name
    }));
    fetchComments();
    fetchSubmissionDetails();
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
        <h3 class="font-semibold mb-2 text-black">Comments</h3>
        <div v-for="comment in comments" :key="comment.id" class="border-b border-gray-200 py-2">
          <p class="text-black"><strong>{{ comment.user.name }}:</strong> {{ comment.content }}</p>
          <p class="text-sm text-black">{{ new Date(comment.created_at).toLocaleString() }}</p>
        </div>
        <div class="mt-4">
          <textarea v-model="newComment" placeholder="Write a comment..." class="w-full p-2 border rounded text-black"></textarea>
          <button @click="submitComment" class="mt-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Post Comment</button>
        </div>
      </div>
    </div>

    <!-- Right Section: Submission Part -->
    <div class="w-full md:w-1/3">
      <div v-if="userRole === 'student'" class="bg-white shadow-md rounded-lg p-4">
        <h3 class="font-semibold mb-2 text-black">Submit Your Work</h3>
        <div v-for="(fileData, index) in selectedFiles" :key="index" class="mt-2">
          <a :href="fileData.url" target="_blank" class="text-blue-500 hover:underline">{{ fileData.file.name }}</a>
        </div>
        <input type="file" multiple @change="handleFileUpload" class="block w-full text-sm text-gray-500
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
      <div v-if ="userRole === 'teacher'" class="bg-white shadow-md rounded-lg p-4 ">
        <h3 class="font-semibold mb-2 text-black">Submissions</h3>
          <div class="w-full flex flex-col gap-3">
            <div @click="openGradeSubmissionModal(submission)" v-for="submission in submissions" :key="submission.id" class="ease-in-out duration-200 md:ml-5 mx-2 flex flex-col rounded-md shadow-md bg-soft-white text-gray-900 dark:text-darkMode border-gray-200 dark:bg-gray-500 dark:border-none dark:hover:text-gray-800 p-2 cursor-pointer group">
              <div class=" flex justify-between items-center dark:group-hover:border-gray-300">
                <h2 class="text-xl">{{ submission.user.name }}</h2>
                <span>{{ new Date(submission.submitted_at).toLocaleDateString() }}</span>
              </div>
              <div class="flex justify-between items-center">
                <span>Grade: {{ submission.grade || 'Not graded yet' }}</span>
              </div>
            </div>
          </div>
      </div>
    </div>


    <Modal v-model="gradeSubmissionModal">
      <div class="modal-content p-4 bg-gray-300 w-3/4 h-fit place-self-center text-black">
        <div>Submission ID = {{clickedSubmission.id}}</div>
        <div>Submission Time = {{clickedSubmission.submitted_at}}</div>
        <div>Student ID = {{clickedSubmission.user?.id}}</div>
        <div>Student Name = {{clickedSubmission.user?.name}}</div>
        <ul>
         <li v-for="(file, index) in clickedSubmission.file_path" :key="index">
            <a :href="file.url" target="_blank" class="text-blue-500 hover:underline">{{ file.original_name }}</a>
         </li>
        </ul>
      </div>
    </Modal>
  </div> 
</template>
