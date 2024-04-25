<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRoute, useRouter } from 'vue-router';
import Modal from '@/components/Modal.vue';
import TopBar from '@/components/TopBar.vue';
import VueFeather from 'vue-feather'

const API = import.meta.env.VITE_LARAVEL_API;
const route = useRoute();
const router = useRouter();
const assignmentId = route.params.assignmentId;
const userRole = route.params.userRole
const gradeSubmissionModal = ref(false)
const fileInput = ref(null);


const isLate = (submittedAt) => {
  const dueDate = new Date(assignment.value.due_date);
  const submissionDate = new Date(submittedAt);
  return submissionDate > dueDate;
};

const removeFile = (index) => {
  selectedFiles.value.splice(index, 1);
  if (fileInput.value) {
    fileInput.value.value = ''; // Reset the file input
  }
};

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
const isSubmitted = ref(false);

const handleFileUpload = (event) => {
  for (let file of event.target.files) {
    const fileData = {
      file: file,
      url: URL.createObjectURL(file) 
    };
    selectedFiles.value.push(fileData);
  }
  event.target.value = ''; 
};

const submitAssignment = async () => {
  if (selectedFiles.value.length === 0) {
    alert('Please select files to submit.');
    return;
  }

  const formData = new FormData();
  selectedFiles.value.forEach((fileData, index) => {
    formData.append(`files[${index}]`, fileData.file); 
  });

  try {
    const response = await axios.post(`${API}assignment/${assignmentId}/submit`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    });
    alert('Assignment submitted successfully.');
   // selectedFiles.value = []; // Optionally clear the selected files after successful submission
    isSubmitted.value = true;
  } catch (error) {
    console.error('Error submitting assignment:', error);
    alert('Failed to submit assignment.');
  }
};

const submissions = ref([]);
var clickedSubmission = ref({});

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

const submitGradeAndFeedback = async () => {
  try {
    await axios.put(`${API}assignment/grade/${clickedSubmission.id}`, {
      grade: clickedSubmission.grade,
      feedback: clickedSubmission.feedback
    });
    alert('Grade and feedback submitted successfully.');
    gradeSubmissionModal.value = false; // Close the modal
    fetchSubmissionDetails(); // Refresh the submissions list to show updated grades and feedback
  } catch (error) {
    console.error('Error submitting grade and feedback:', error);
    alert('Failed to submit grade and feedback.');
  }
};

const studentSubmissionDetails = ref(null);

const fetchStudentSubmissionDetails = async () => {
  try {
    const response = await axios.get(`${API}assignment/${assignmentId}/studentSubmissionDetails`);
    studentSubmissionDetails.value = response.data;
  } catch (error) {
    console.error('Error fetching student submission details:', error);
  }
};

const deleteAssignment = async () => {
  if (!confirm('Are you sure you want to delete this assignment?')) {
    return;
  }

  try {
    await axios.delete(`${API}assignment/${assignmentId}`);
    alert('Assignment deleted successfully.');
    router.push(`/teacher/${route.params.userId}/classroom/${route.params.classroomId}`); 
  } catch (error) {
    console.error('Error deleting assignment:', error);
    alert('Failed to delete assignment.');
  }
};


onMounted(async () => {
  try {
    const response = await axios.get(`${API}assignment/show/${assignmentId}`);
    assignment.value = response.data;
    // Modify the file structure to include the original file name
    assignment.value.files = assignment.value.files?.map(file => ({
      url: file.url,
      originalName: file.original_name
    }));
    fetchComments();
    fetchSubmissionDetails();
    if (userRole === 'student') {
      await fetchStudentSubmissionDetails(); // Fetch student submission details if the user is a student
    }
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
  <TopBar />
  <div class="flex flex-col md:flex-row p-4 gap-4">
    <!-- Left Section: Assignment Details -->
    <div class="flex-1">
      <div class="bg-white shadow-md rounded-lg p-4" style="max-height: 500px; overflow-y: auto;">
        <div class="flex justify-between items-center">
          <h2 class="text-2xl font-bold mb-2 text-black">{{ assignment.title }}</h2>
          <button @click="deleteAssignment" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
            Delete
          </button>
        </div>
        <p class="text-gray-700">{{ assignment.description }}</p>
        <p class="text-gray-600">Due date: {{ new Date(assignment.due_date).toLocaleString() }}</p>
        <div class="mt-4">
          <h2 class="font-semibold mb-2 text-black">Attached Files:</h2>
          <ul class="grid grid-cols-2 gap-4">
            <li v-for="(file, index) in assignment.files" :key="index" class="border rounded-lg p-4 flex items-center gap-4">
              <div v-if="file.type && file.type.includes('image')" class="flex-shrink-0">
                <img :src="file.url" alt="file.originalName" class="h-20 w-20 object-cover rounded">
              </div>
              <div v-else-if="file.type && file.type.includes('text')" class="flex-shrink-0">
                <vue-feather type="file-text" class="w-14 h-14 text-gray-500"></vue-feather>
              </div>
              <div v-else class="flex-shrink-0">
                <vue-feather type="file" class="w-14 h-14 text-gray-500"></vue-feather>
              </div>
              <a :href="file.url" target="_blank" class="flex-grow text-blue-500 hover:underline">{{ file.originalName }}</a>
            </li> 
          </ul>
        </div>
      </div>
      <!-- Comment Section -->
      <div class="mt-4 bg-white shadow-md rounded-lg p-4">
        <h3 class="font-semibold mb-2 text-black">Comments</h3>
        <div class="max-h-[calc(100vh-250px)] overflow-y-auto">
          <div v-for="comment in comments" :key="comment.id" class="border-b border-gray-200 py-2 flex justify-between">
            <p class="text-black"><strong>{{ comment.user.name }}:</strong> {{ comment.content }}</p>
            <div class="text-sm text-black text-right">
              {{ new Date(comment.created_at).toLocaleString() }}
            </div>
          </div>
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
        <div v-for="(fileData, index) in selectedFiles" :key="index" class="mt-2 pb-3 flex items-center">
          <div class="flex-grow flex items-center gap-3 border rounded-lg p-3">
            <div v-if="fileData.file.type.includes('image')" class="flex items-center gap-3">
              <img :src="fileData.url" class="h-16 w-16 object-cover rounded">
              <a :href="fileData.url" target="_blank" class="flex-grow text-blue-500 hover:underline">{{ fileData.file.name }}</a>
            </div>

            <div v-else-if="fileData.file.type.includes('text')" class="flex items-center gap-3">
              <vue-feather type="file-text" class="w-10 h-10 text-gray-500"></vue-feather>
              <a :href="fileData.url" target="_blank" class="flex-grow text-blue-500 hover:underline">{{ fileData.file.name }}</a>
            </div>

            <div v-else class="flex items-center gap-3">
              <vue-feather type="file" class="w-10 h-10 text-gray-500"></vue-feather>
              <a :href="fileData.url" target="_blank" class="flex-grow text-blue-500 hover:underline">{{ fileData.file.name }}</a>
            </div>
            <button @click="removeFile(index)" class="ml-auto bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">X</button>
          </div>
        </div>
        <input type="file" multiple @change="handleFileUpload" ref="fileInput" class="block w-full text-sm text-gray-500
          file:mr-4 file:py-2 file:px-4
          file:rounded-full file:border-0
          file:text-sm file:font-semibold
          file:bg-blue-50 file:text-blue-700
          hover:file:bg-blue-100
        "/>
        <button v-if="!isSubmitted" @click="submitAssignment" class="mt-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
          Submit
        </button>
        <p v-else class="mt-2 text-green-600 font-bold">Submitted</p>
        <div v-if="studentSubmissionDetails && (studentSubmissionDetails.grade || studentSubmissionDetails.feedback)">
          <p class="text-black"><strong>Grade:</strong> {{ studentSubmissionDetails.grade || 'Not graded yet' }}</p>
          <p class="text-black"><strong>Feedback:</strong> {{ studentSubmissionDetails.feedback || 'No feedback provided yet.' }}</p>
        </div>
      </div>
      <!--teacher check submission section-->
      <div v-if="userRole === 'teacher'" class="bg-white shadow-md rounded-lg p-4 ">
        <h3 class="font-semibold mb-2 text-black">Submissions</h3>
        <div class="w-full flex flex-col gap-3">
          <div 
            v-for="submission in submissions" 
            :key="submission.id" 
            @click="openGradeSubmissionModal(submission)" 
            :class="{'bg-red-500': isLate(submission.submitted_at), 'bg-soft-white': !isLate(submission.submitted_at)}" 
            class="ease-in-out duration-200 md:ml-5 mx-2 flex flex-col rounded-md shadow-md text-gray-900 dark:text-darkMode border-gray-200 dark:bg-gray-500 dark:border-none dark:hover:text-gray-800 p-2 cursor-pointer group"
          >
            <div class="flex justify-between items-center dark:group-hover:border-gray-300">
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
    <div class="modal-content p-4 bg-white shadow-xl rounded-lg w-3/4 h-fit place-self-center text-black">
      <h3 class="text-xl font-bold mb-4">Grade Submission</h3>
      <div class="mb-2">
        <span class="font-semibold">Submission ID:</span> {{clickedSubmission.id}}
      </div>
      <div class="mb-2">
        <span class="font-semibold">Submission Time:</span> {{ new Date(clickedSubmission.submitted_at).toLocaleString() }}
      </div>
      <div class="mb-2">
        <span class="font-semibold">Student ID:</span> {{clickedSubmission.user?.id}}
      </div>
      <div class="mb-4">
        <span class="font-semibold">Student Name:</span> {{clickedSubmission.user?.name}}
      </div>
      <div class="mb-4">
        <h4 class="font-semibold mb-2">Submitted Files:</h4>
        <ul class="list-disc pl-5">
          <li v-for="(file, index) in clickedSubmission.file_path" :key="index" class="mb-2">
            <div v-if="file.type && file.type.includes('image')" class="flex items-center">
              <img :src="file.url" alt="file.original_name" class="h-20 w-20 object-cover rounded mr-2">
              <a :href="file.url" target="_blank" class="text-blue-500 hover:underline">{{ file.original_name }}</a>
            </div>
            <div v-else-if="file.type && file.type.includes('text')" class="flex items-center">
              <vue-feather type="file-text" class="w-6 h-6 mr-2 text-gray-500"></vue-feather>
              <a :href="file.url" target="_blank" class="text-blue-500 hover:underline">{{ file.original_name }}</a>
            </div>
            <div v-else class="flex items-center">
              <vue-feather type="file" class="w-6 h-6 mr-2 text-gray-500"></vue-feather>
              <a :href="file.url" target="_blank" class="text-blue-500 hover:underline">{{ file.original_name }}</a>
            </div>
          </li>
        </ul>
      </div>
      <div class="mb-4">
        <label for="grade" class="block font-semibold mb-1">Grade (0-100):</label>
        <input type="number" id="grade" v-model="clickedSubmission.grade" min="0" max="100" class="border-2 rounded w-full p-2">
      </div>
      <div class="mb-4">
        <label for="feedback" class="block font-semibold mb-1">Feedback:</label>
        <textarea id="feedback" v-model="clickedSubmission.feedback" rows="4" class="border-2 rounded w-full p-2"></textarea>
      </div>
      <button @click="submitGradeAndFeedback" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Submit Grade and Feedback
      </button>
    </div>
  </Modal>
  </div> 
</template>
