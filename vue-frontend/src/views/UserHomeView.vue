<script setup>
import TopBar from '@/components/TopBar.vue';
import Classroom from "@/components/Classroom.vue";
import Arrow from "@/components/Arrow.vue";
import Modal from "@/components/Modal.vue";
import Notification from "@/components/Notification.vue";
import VueFeather from 'vue-feather'; 

import axios from "axios";
import Pusher from 'pusher-js';
import Echo from 'laravel-echo';

import { onMounted, ref, nextTick } from "vue";
import { RouterLink, useRoute, useRouter } from "vue-router";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

const API = import.meta.env.VITE_LARAVEL_API
const PUSHER_KEY = import.meta.env.VITE_PUSHER_APP_KEY;
const PUSHER_CLUSTER = import.meta.env.VITE_PUSHER_APP_CLUSTER;

const route = useRoute()
const router = useRouter()
const userId = route.params.userId
const userRole = route.params.userRole
const userData = ref({})

const classrooms = ref([])
const classroomCode = ref('')
const classroomName = ref('')
const classroomDesc = ref('')
const maxMembers = ref(null);
const selectedType = ref('anyone_can_join'); //default

const joinOrCreateClassroomBtn = ref(true)
const modalEnabled = ref(false)
const modalInput = ref(null)
const currentUserMode = ref('')

const notifs = ref([])
const retainedNotifs = ref([])
const showNotifs = ref(false)


Pusher.logToConsole = true;

window.Echo = new Echo({
  broadcaster: 'pusher',
  key: PUSHER_KEY,
  cluster: PUSHER_CLUSTER,
  forceTLS: true,
  encrypted: true,
  authorizer: (channel, options) => {
    return {
      authorize: async (socketId, callback) => {
        await axios.post('http://localhost:8000/broadcasting/auth', {
          socket_id: socketId,
          channel_name: channel.name
        })
          .then(response => {
            callback(null, response.data);
          })
          .catch(error => {
            callback(error);
          });
      }
    };
  },
});

console.log(window.Echo)

function TaskNotif() {
  if (userRole === 'student') {
    window.Echo.private('App.Models.User.' + userId)
      .notification((notification) => {
        notifs.value.push(notification);
        console.log(notifs.value)
        setTimeout(() => {
          notifs.value.shift();
          retainedNotifs.value.push(notification);
        }, 10000);
      });
  }
}

function displayToastMessage(message) {
  toast(message, {
    autoClose: 3000, // Auto close after 3 seconds
    type: "info", // Type of toast - info, success, error, etc.
    position: "top-right", // Position of toast on the screen
    // You can add more options here as per your requirements
  });
}

async function getUserDetails() {
  try {
    const res = await axios.get(`${API}user`, {params: { id: userId }});
    const userData = res.data;
    currentUserMode.value = userData.mode; // Correctly set the user's current mode
    console.log('User data:', userData);
  } catch (error) {
    console.error('Error fetching user details:', error);
  }
}

function closeNotif() {
  
}

async function getClassrooms() {
  const response = await axios.get(API + 'user/classrooms/' + userId)
  classrooms.value = response.data
  joinOrCreateClassroomBtn.value = (classrooms.value.length === 0) ? true : false
}

async function joinClassroom() {
  const join = await axios.put(API + 'student/' + userId + '/classroom-join/' + classroomCode.value)
  console.log(join.data)

  if (join.data.status === 'success') {
    let classroomId = join.data.classroom_id
    router.push({ name: 'classroom', params: { classroomId: classroomId } })
  }
  else if (join.data.status === 'already joined') {
    alert(join.data.message)
  }
  else if (join.data.status === 'classroom not found') {
    alert(join.data.message)
  }
}

async function createClassroom() {
  if (!maxMembers.value || isNaN(maxMembers.value) || maxMembers.value <= 0) {
    console.error("Invalid number")
    alert("Classroom Size must be at least 1")
    return;
  }

  const classroomData = {
    classroom_name: classroomName.value,
    classroom_desc: classroomDesc.value,
    maxMembers: maxMembers.value,
    type: selectedType.value,
  }
  try {
    const res = await axios.post(API + 'classroom/create', classroomData)
    if (res.data.status === 'success') {
      router.push({
        name: 'classroom',
        params: {
          classroomId: res.data.classroom_id
        }
      })
    }
  } catch (err) {
    console.log(err)
  }
}

function enableModal() {
  modalEnabled.value = true
  nextTick(() => {
    modalInput.value.focus()
  })
}

onMounted(() => {
  getClassrooms();
  TaskNotif();
  getUserDetails();
  window.Echo.private(`chatroom.${userId}`)
    .listen('.message-sent', (e) => {
      const message = e.message;
      const senderName = e.user.name;
      const toastMessage = `New message from ${senderName}: ${message.message}`;
      console.log("Received message: ", e);

      if (currentUserMode.value.toLowerCase() !== 'do not disturb') {
          displayToastMessage(toastMessage);
      }
      
    });
})
</script>

<template>
  <div class="h-screen dark:bg-soft-black bg-soft-white">
    <TopBar @modal-enabled="enableModal" v-model:enable-button="joinOrCreateClassroomBtn" />

    <!-- Arrow pointing to "Join Classroom" button -->
    <div v-if="userRole === 'student' && classrooms.length === 0" class="absolute flex flex-rows left-32">
      <Arrow class="w-16" />
      <p class="pt-11">Click here to join a classroom!</p>
    </div>

    <div v-if="userRole === 'teacher' && classrooms.length === 0" class="absolute flex flex-rows left-32">
      <Arrow class="w-16" />
      <p class="pt-11">Click here to create a classroom!</p>
    </div>
    <!-- ----- -->

    <div v-if="classrooms.length === 0" class="flex justify-center h-screen">
      <div class="wrapper flex flex-col justify-center">
        <p class="text-2xl text-center">No classrooms found!</p>
      </div>
    </div>

    <!-- List of Classroom Boxes -->
    <div v-else class="flex justify-center">
      <div class="sm:w-3/4 w-full flex flex-col sm:flex-row sm:flex-wrap content-start">
        <div v-for="(item, index) of classrooms" :key="index" class="h-fit">
          <Classroom v-model="classrooms[index]" />
        </div>

        <!-- Join Classroom (for classrooms no.>= 1)-->
        <button @click="enableModal" v-if="classrooms.length && userRole === 'student'"
          class="cursor-pointer hover:dark:border-gray-100 hover:dark:text-gray-100 hover:text-gray-900 hover:border-gray-900  ease-in-out duration-500 my-4 mx-4 rounded-md border-dashed border-2 border-gray-500 bg-transparent text-gray-500 h-48 sm:w-48 overflow-hidden w-full">
          <div class="w-full h-full text-2xl flex flex-col justify-items-center justify-center">
            <p class="place-self-center font-bold">+</p>
            <p class="place-self-center font-bold text-center">Join More Classrooms</p>
          </div>
        </button>

        <!-- Create Classroom -->
        <button @click="enableModal" v-else-if="classrooms.length && userRole === 'teacher'"
          class="hover:border-gray-900 hover:text-gray-900 dark:hover:border-gray-100 dark:hover:text-gray-100 ease-in-out duration-500 my-4 mx-4 rounded-md border-dashed border-2 border-gray-500 bg-transparent text-gray-500 h-48 sm:w-48 overflow-hidden">
          <div class="w-full h-full text-2xl flex flex-col justify-items-center justify-center">
            <p class="place-self-center font-bold">+</p>
            <p class="place-self-center font-bold text-center">Create More Classrooms</p>
          </div>
        </button>
      </div>
    </div>
    <!-- --------- -->

    <!-- Modals -->
    <Modal v-if="userRole === 'student'" v-model="modalEnabled">
      <form @submit.prevent class="bg-gray-600 rounded-md w-1/4 p-4 h-fit place-self-center">
        <p class="text-2xl text-bold">Enter classroom code</p>
        <input ref="modalInput" v-model="classroomCode" type="text" placeholder="Classroom Code"
          class="text-black border-2 w-full border-gray-300 rounded-md p-2 my-2" />
        <div class="float-right flex flex-row">
          <button @click="joinClassroom"
            class="bg-blue-400 rounded-md hover:bg-sky-200 text-black ease-in-out duration-300 px-4 py-1 mx-4 mt-1">
            Join classroom </button>
          <button @click="modalEnabled = !modalEnabled"> Cancel </button>
        </div>
      </form>
    </Modal>

    <Modal v-else v-model="modalEnabled">
      <form @submit.prevent class="bg-gray-600 rounded-md w-2/4 p-5 h-fit place-self-center ">
        <!-- Adjusted width to 3/4 -->
        <p class="text-3xl text-bold text-center"> Create new classroom </p>
        <div class="flex flex-col text-black">
          <input ref="modalInput" type="text" v-model="classroomName" placeholder="Classroom Name"
            class="text-black border-2 border-gray-300 rounded-md p-2 my-2">
          <div class="flex flex-row space-x-4 my-4"> <!-- Added margin here -->
            <input v-model="maxMembers" type="number" placeholder="Classroom Size (e.g. 30)"
              class="text-black border-2 border-gray-300 rounded-md p-2 flex-1" min="0"
              oninput="this.value = Math.abs(this.value)">
            <select v-model="selectedType" class="text-black border-2 border-gray-300 rounded-md p-2 flex-1">
              <option value="anyone_can_join">Anyone can join</option>
              <option value="invite_only">Invite only</option>
            </select>
          </div>
          <textarea v-model="classroomDesc" placeholder="Description (e.g. This is a description of the classroom...)"
            class="text-black border-2 border-gray-300 rounded-md p-2 my-2 h-60 resize-none break-words"></textarea>
          <!-- Adjusted height to h-64 -->
        </div>

        <div class="float-right flex flex-row">
          <button @click="createClassroom"
            class="bg-blue-400 rounded-md hover:bg-sky-200 text-black ease-in-out duration-300 p-2 m-2">Create
            Classroom</button>
          <button @click="modalEnabled = !modalEnabled"> Cancel </button>
        </div>
      </form>
    </Modal>
    <!-- -----  -->
    

    <Notification class="w-1/4">
      <div v-for="(notif, index) in notifs" id="toast-default" class="flex items-center w-full max-w-xs p-4 text-gray-500 bg-white rounded-lg shadow-md border border-gray-300 dark:text-gray-400 dark:bg-gray-800" role="alert">
        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-blue-500 bg-blue-100 rounded-lg dark:bg-blue-800 dark:text-blue-200">
          <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 20">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.147 15.085a7.159 7.159 0 0 1-6.189 3.307A6.713 6.713 0 0 1 3.1 15.444c-2.679-4.513.287-8.737.888-9.548A4.373 4.373 0 0 0 5 1.608c1.287.953 6.445 3.218 5.537 10.5 1.5-1.122 2.706-3.01 2.853-6.14 1.433 1.049 3.993 5.395 1.757 9.117Z"/>
          </svg>
          <span class="sr-only">Fire icon</span>
        </div>
        <div v-if="notif.due_date" class="p-2">
          <p class="text-2xl"> You got a new quiz! </p>
          <p class="text-lg"> {{ notif.title }} </p>
          <button>
            <RouterLink :to="{ name: 'classroom', params: { classroomId: notif.classroom_id }}"> Check it out </RouterLink>
          </button>
        </div>
        <div v-else="notif.due_date" class="p-2">
          <p class="text-2xl"> You got a new assignment! </p>
          <p class="text-lg"> {{ notif.title }} </p>
          <button class="rounded-md bg-purple-400 text-black px-2">
            <RouterLink :to="{ name: 'classroom', params: { classroomId: notif.classroom_id }}">Check it out</RouterLink>
          </button>
        </div>
        <button @click="notifs.shift()" type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" data-dismiss-target="#toast-default" aria-label="Close">
          <span class="sr-only">Close</span>
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
          </svg>
        </button>
      </div>
    </Notification>

    <div @click="showNotifs = !showNotifs" v-if="retainedNotifs.length" class="rounded-t-md p-2 cursor-pointer dark:bg-gray-600 bg-gray-400 text-white dark:text-darkMode absolute bottom-0 right-10">
      <span v-show="showNotifs===false">
        <vue-feather type="bell"></vue-feather>
        <vue-feather type="circle" size="9" fill="red" stroke="white" class="absolute right-2 top-1"></vue-feather>
      </span>
      <span v-show="showNotifs===true" class="float-right hover:bg-red-600 rounded-md px-2 mb-2 border-b-2 border-b-red-500 w-full bg-red-400 text-black hover:text-white font-jetBrains font-bold text-center">Close Notification</span>

      <div v-show="showNotifs">
        <RouterLink :to="{ name: 'classroom', params: { classroomId: notif.classroom_id }}" v-for="(notif, index) in retainedNotifs" :key="index" class="hover:bg-gray-300 hover:text-gray-900 flex shadow-md bg-gray-500 p-2 rounded-md my-2 gap-3">
          <div class="self-center inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-blue-500 bg-blue-100 rounded-lg dark:bg-blue-800 dark:text-blue-200">
            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 20">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.147 15.085a7.159 7.159 0 0 1-6.189 3.307A6.713 6.713 0 0 1 3.1 15.444c-2.679-4.513.287-8.737.888-9.548A4.373 4.373 0 0 0 5 1.608c1.287.953 6.445 3.218 5.537 10.5 1.5-1.122 2.706-3.01 2.853-6.14 1.433 1.049 3.993 5.395 1.757 9.117Z"/>
            </svg>
            <span class="sr-only">Fire icon</span>
          </div>
          <span v-if="notif.due_date">
            <p class="text-lg font-bold"> New quiz </p>
            <p class="text-md"> {{ notif.title }} </p>
          </span>
          <span v-else>
            <p class="text-lg font-bold"> New assignment </p>
            <p class="text-md"> {{ notif.title }} </p>
          </span>
          <div class="rounded-md hover:bg-gray-400/50 self-center flex">
            <vue-feather type="x" class="hover:text-gray-100 self-center"></vue-feather>
          </div>
        </RouterLink>
      </div>
    </div>


  </div>
</template>
