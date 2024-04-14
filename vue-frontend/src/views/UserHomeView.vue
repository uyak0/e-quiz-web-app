<script setup>
  import TopBar from '@/components/TopBar.vue';
  import Classroom from "@/components/Classroom.vue";
  import Arrow from "@/components/Arrow.vue";
  import Modal from "@/components/Modal.vue";
  import axios from "axios";
  import { onMounted, ref, nextTick} from "vue";
  import { useRoute, useRouter } from "vue-router";

  const API = import.meta.env.VITE_LARAVEL_API
  const route = useRoute()
  const router = useRouter()
  const userId = route.params.userId
  const userRole = route.params.userRole

  const classrooms = ref([])
  const classroomCode = ref('')
  const classroomName = ref('')
  const classroomDesc = ref('')

  const joinOrCreateClassroomBtn = ref(true)
  const modalEnabled = ref(false)
  const modalInput = ref(null)

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
      router.push({ name: 'classroom', params: { classroomId: classroomId }}) 
    }
    else if (join.data.status === 'already joined') {
      alert(join.data.message)
    }
    else if (join.data.status === 'classroom not found') {
      alert(join.data.message) 
    }
  }

  async function createClassroom() {
    const classroomData = {
      classroom_name: classroomName.value,
      classroom_desc: classroomDesc.value,
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
  })
</script>

<template>
  <div class="h-screen dark:bg-soft-black bg-soft-white">
    <TopBar @modal-enabled="enableModal" v-model:enable-button="joinOrCreateClassroomBtn"/>

    <!-- Arrow pointing to "Join Classroom" button -->
    <div v-if="userRole === 'student' && classrooms.length === 0" class="absolute flex flex-rows left-32">
      <Arrow class="w-16"/>
      <p class="pt-11">Click here to join a classroom!</p>
    </div>

    <div v-if="userRole === 'teacher' && classrooms.length === 0" class="absolute flex flex-rows left-32">
      <Arrow class="w-16"/>
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
        <button @click="enableModal" v-if="classrooms.length && userRole === 'student'" class="cursor-pointer hover:border-gray-100 hover:text-gray-100 ease-in-out duration-500 my-4 mx-4 rounded-md border-dashed border-2 border-gray-500 bg-transparent text-gray-500 h-48 sm:w-48 overflow-hidden w-full">
          <div class="w-full h-full text-2xl flex flex-col justify-items-center justify-center">
            <p class="place-self-center font-bold">+</p>
            <p class="place-self-center font-bold text-center">Join More Classrooms</p>
          </div>
        </button>

        <!-- Create Classroom -->
        <button @click="enableModal" v-else-if="classrooms.length && userRole === 'teacher'" class="hover:border-gray-900 hover:text-gray-900 dark:hover:border-gray-100 dark:hover:text-gray-100 ease-in-out duration-500 my-4 mx-4 rounded-md border-dashed border-2 border-gray-500 bg-transparent text-gray-500 h-48 sm:w-48 overflow-hidden">
          <div class="w-full h-full text-2xl flex flex-col justify-items-center justify-center">
            <p class="place-self-center font-bold">+</p>
            <p class="place-self-center font-bold text-center">Create More Classrooms</p>
          </div>
        </button>
      </div>
    </div>
    <!-- --------- -->

    <!-- Modals -->
    <Modal v-if="userRole==='student'" v-model="modalEnabled">
      <form @submit.prevent class="bg-gray-600 rounded-md w-1/4 p-4 h-fit place-self-center">
        <p class="text-2xl text-bold">Enter classroom code</p>
        <input ref="modalInput" v-model="classroomCode" type="text" placeholder="Classroom Code" class="text-black border-2 w-full border-gray-300 rounded-md p-2 my-2" />
        <div class="float-right flex flex-row">
          <button @click="joinClassroom" class="bg-blue-400 rounded-md hover:bg-sky-200 text-black ease-in-out duration-300 px-4 py-1 mx-4 mt-1"> Join classroom </button>
          <button @click="modalEnabled = !modalEnabled"> Cancel </button>
        </div>
      </form>
    </Modal>

    <Modal v-else v-model="modalEnabled">
      <form @submit.prevent class="bg-gray-600 rounded-md w-1/4 p-4 h-fit place-self-center">
        <p class="text-2xl text-bold"> Create Classroom </p>
        <div class="flex flex-col text-black">
          <input ref="modalInput" type="text" v-model="classroomName" placeholder="Classroom Name" class="text-black border-2 border-gray-300 rounded-md p-2 my-2">
          <input type="text" v-model="classroomDesc" placeholder="Description" class="text-black border-2 border-gray-300 rounded-md p-2 my-2">
        </div>

        <div class="float-right flex flex-row">
          <button @click="createClassroom" class="bg-blue-400 rounded-md hover:bg-sky-200 text-black ease-in-out duration-300 p-2 m-2">Create Classroom</button>
          <button @click="modalEnabled = !modalEnabled"> Cancel </button>
        </div>
      </form>
    </Modal>
    <!-- -----  -->
  </div>
</template>
