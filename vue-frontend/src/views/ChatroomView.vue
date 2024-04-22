<template>
  <!--Header-->
  <div class="bg-white border b border-gray-300 fixed top-0 w-full shadow">
    <div class="lg:container mx-auto p-4">
      <div class="grid grid-cols-3 gap-4">
        <div class="col-span-1 min-w-[250px]">
          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <img :src="fileLink('UserProfile.png')" class="w-12 h-12 rounded-full border-2 border-blue-400"
                alt="user">
              <span class="font-semibold text-xl pl-1 text-black">{{ userData?.name }}</span>
            </div>
            <!-- <div class="relative inline-block text-left group">
              <three-dots-icon class="w-6 h-6 cursor-pointer"></three-dots-icon>
              <div class="origin-top-right absolute right-0 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50 hidden group-hover:block">
                <div class="py-1">
                    <a href="" @click="navigateToProfile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 cursor-pointer">Profile</a>
                  <a href="" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 cursor-pointer" @click="logout">Logout</a>
                </div>
              </div>
            </div> -->
          </div>
        </div>
        <div class="col-span-2 text-right">
          <button class="bg-red-500 text-white px-4 py-2 rounded-md" @click="logout">Logout</button>
        </div>
      </div>
    </div>
  </div>

  <div class="lg:container mx-auto mt-[90px] px-4 mb-4">
    <div class="grid grid-cols-3 gap-4">
      <div class="col-span-1 min-w-[300px] bg-white p-4 shadow-md rounded-md">
        <!--Search Bar-->
        <input type="text" v-model="searchQuery" placeholder="Search"
          class="w-full p-2 rounded-md border border-gray-300 focus:outline-none focus:ring focus:border-blue-400 mb-4 text-black">

        <!--List-->
        <div class="max-h-96 overflow-y-auto">
          <div v-for="user in filteredUsers" :key="user.id"
            class="flex p-2 items-center mb-3 cursor-pointer rounded-md bg-gray-100" @click="handleUserClick(user)">
            <div class="relative">
              <img :src="fileLink('UserProfile.png')" class="w-12 h-12 rounded-full border-2 border-blue-400"
                :alt="user.name">
              <div class="absolute h-3 w-3 bg-slate-400 rounded-full -top-1.5 -left-1.5 ml-2"></div>
            </div>
            <div class="flex justify-between w-full ml-1.5">
              <p class="font-semibold text-black">{{ user.name }}</p>
              <span class="bg-red-500 text-white text-xs rounded-full px-2 py-0.5"
                v-if="user.unseen_messages.length > 0">
                {{ user.unseen_messages.length }}
              </span>
            </div>
          </div>
        </div>
      </div>


      <div class="col-span-2 bg-stone-50 shadow-md rounded-md">

        <div v-if="isChatOpen">

          <!--Chat Header-->
          <div class="flex items-center justify-between mb-4 bg-slate-200 px-4 pt-2 pb-2 rounded-tl-md rounded-tr-md">
            <div class="flex items-center">
              <img :src="fileLink('UserProfile.png')" class="w-12 h-12 rounded-full border-2 border-blue-400"
                alt="User">
              <div class="ml-3">
                <p class="font-semibold text-black">{{ selectedUser.name }}</p>

              </div>
            </div>
            <div class="relative inline-block text-left group">
              <three-dots-icon class="w-6 h-6 cursor-pointer"></three-dots-icon>

              <div
                class="origin-top-right absolute right-0 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50 hidden group-hover:block">
                <div class="py-1">
                  <a href=""
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 cursor-pointer">Close
                    Chat</a>

                </div>
              </div>
            </div>
          </div>

                   <!-- Chat Body -->
                    <div class="overflow-y-auto max-h-64 min-h-[19rem] px-4" ref="chatContentRef" @scroll="handleChatScroll">
                        <div v-for="message in userMessages" :key="message.id">
                            <!--Receiver Message -->
                            <div v-if="message.sender_id != userId" class="flex items-center justify-start mb-4">
                                <img :src="fileLink('UserProfile.png')" class="w-6 h-6 rounded-full border-2 border-blue-400" alt="User">
                                <div class="relative group text-sm p-2 shadow bg-white rounded-md max-w-xs text-black break-words">
                                    <!-- Check if message is an image URL and render accordingly -->
                                    <div v-if="isImageUrl(message.message)">
                                        <img :src="`http://localhost:8000${message.message.replace(/\\/g, '')}`" alt="Image" class="max-w-full h-auto max-h-96 image-fit cursor-pointer" @click="showLargeImage(`http://localhost:8000${message.message.replace(/\\/g, '')}`)" @load="scrollToChatBottom">
                                    </div>
                                    <!-- Check if message is a document URL -->
                                    <div v-else-if="isDocumentUrl(message.message)">
                                        <a v-if="isDocumentUrl(message.message)" :href="getDocumentUrl(message.message)" target="_blank" class="text-blue-500 hover:underline">{{ getDocumentName(message.message) }}</a>
                                    </div>
                                    <div v-else>
                                        {{ message.message }}
                                    </div>
                                    <div class="absolute top-1/2 -translate-y-1/2 left-full ml-1 hidden group-hover:block bg-gray-600 py-1 px-1.5 rounded z-50 text-white w-max">{{ formatMessageTime(message.created_at) }}</div>
                                </div>
                                <three-dots-icon class="w-4 h-4 cursor-pointer"></three-dots-icon>
                            </div>
                            <!--Sender Message -->
                            <div v-else class="flex items-center justify-end mb-4">
                                <three-dots-icon class="w-4 h-4 cursor-pointer"></three-dots-icon>
                                <div class="relative group text-sm p-2 shadow bg-indigo-100 rounded-md max-w-xs text-black break-words">
                                    <!-- Check if message is an image URL and render accordingly -->
                                    <div v-if="isImageUrl(message.message)">
                                        <img :src="`http://localhost:8000${message.message.replace(/\\/g, '')}`" alt="Image" class="max-w-full h-auto max-h-96 cursor-pointer" @click="showLargeImage(`http://localhost:8000${message.message.replace(/\\/g, '')}`)" @load="scrollToChatBottom">
                                    </div>
                                    <!-- Check if message is a document URL -->
                                    <div v-else-if="isDocumentUrl(message.message)">
                                        <a v-if="isDocumentUrl(message.message)" :href="getDocumentUrl(message.message)" target="_blank" class="text-blue-500 hover:underline">{{ getDocumentName(message.message) }}</a>
                                    </div>
                                    <div v-else>
                                        {{ message.message }}
                                    </div>
                                    <div class="absolute top-1/2 -translate-y-1/2 right-full mr-1 hidden group-hover:block bg-gray-600 py-1 px-1.5 rounded z-50 text-white w-max">{{ formatMessageTime(message.created_at) }}</div>
                                </div>
                                <img :src="fileLink('UserProfile.png')" class="w-6 h-6 rounded-full border-2 border-blue-400" alt="User">
                            </div>
                        </div>
                    </div>

                    <!--Chat Footer-->
                    <div class="flex items-center p-4 bg-white rounded-bl-md rounded-br-md">
                        <div class="relative group">
                        <!-- Hover content with transition for smoother appearance/disappearance -->
                        <div class="absolute bottom-full mb-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col">
                        <div class="bg-blue-500 text-white px-4 py-2 rounded-md mb-1 flex items-center cursor-pointer" @click="triggerFileInput">
                            <image-icon class="w-7 h-7"></image-icon>
                            <p class="font-semibold text-sm ml-2">Image</p>
                            <input type="file" ref="fileInput" class="hidden" @change="sendMediaMessage" accept="image/*" />
                        </div>
                        <div class="bg-purple-500 text-white px-4 py-2 rounded-md flex items-center cursor-pointer" @click="triggerDocumentInput">
                            <docoument-icon class="w-7 h-7"></docoument-icon>
                            <p class="font-semibold text-sm ml-2">Document</p>
                            <input type="file" ref="documentInput" class="hidden" @change="sendDocumentMessage" accept=".pdf,.doc,.docx,.txt" />
                        </div>
                        </div>
                        <!-- Icon -->
                        <add-icon class="w-10 h-10 -ml-3 cursor-pointer"></add-icon>
                    </div>

                        <input type="text" v-model="messageContent" placeholder="Type your message here"  class="w-full p-2 rounded-md border border-gray-300 focus:outline-none focus:ring focus:border-blue-400  text-black" @keyup.enter="submitMessage">
                        <button @click="submitMessage" class="bg-blue-600 text-white px-4 py-2 rounded-md disabled:bg-gray-400 ml-2" >Send</button>
                    </div>
                </div>
                
                <div v-else class="flex flex-col items-center justify-center min-h-[19rem]">
                    
                    <p class="text-2xl font-semibold mt-20 text-black">E-Quiz Web App</p>
                    <p class="text-gray-500">Start a conversation</p>
                </div>
                
            </div>
        </div>
    </div>
    <!-- Image Modal -->
    <div v-if="selectedImageUrl" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center" @click="selectedImageUrl = ''">
        <img :src="selectedImageUrl" style="max-width: 100%; max-height: 95%;" @click.stop>
    </div>
</template>

<script setup>
import axios from 'axios';
import ThreeDotsIcon from "@/components/icons/ThreeDotsIcon.vue";
import AddIcon from "@/components/icons/AddIcon.vue";
import _ from 'lodash';
import ImageIcon from "@/components/icons/ImageIcon.vue";
import DocoumentIcon from "@/components/icons/DocoumentIcon.vue";
import {useRoute, useRouter } from "vue-router";
import { ref, onMounted, provide, reactive, watch, computed, nextTick} from 'vue'; // Import onMounted to fetch data when the component is mounted

import Pusher from 'pusher-js';
import Echo from 'laravel-echo';

import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

const API = import.meta.env.VITE_LARAVEL_API;
const PUSHER_CLUSTER = import.meta.env.VITE_PUSHER_APP_CLUSTER;
const PUSHER_APP_KEY = import.meta.env.VITE_PUSHER_APP_KEY;
    
    const emits = defineEmits(['onCloseChat'])
    const route = useRoute();
    const router = useRouter();
    const user = ref({});
    const userId = route.params.userId
    const userRole = route.params.userRole
    const searchQuery = ref(route.query.search || '');
    const messageContent = ref("");
    const userMessages = ref([]);
    const userData = ref({})
    const emittedMessage = ref(null);
    const onlineUsers = ref([]);
    const isChatOpen = ref(false)
    const selectedUser = ref(null)
    const chatContentRef = ref(null);
    const fileInput = ref(null);
    const selectedImageUrl = ref('');
    const documentInput = ref(null); 
    const currentUserMode = ref('');
    let scrollPoint = ref(0);

// Enable pusher logging - don't include this in production
Pusher.logToConsole = true;

// Echo Initialization
window.Echo = new Echo({
  broadcaster: 'pusher',
  key: PUSHER_APP_KEY,
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

const chatPanels = reactive({ panels: [] });

        async function getUser() {
            try {
                const res = await axios.get(`${API}user`, {params: { id: userId }});
                userData.value = res.data
                currentUserMode.value = userData.value.mode; // toast message respect to receiver preferences instead sender preferences of mode
                console.log('User data:', userData.value);
            } catch (error) {
                console.error('Error fetching user:', error);
            }
        }

async function getOnlineUsers() {
  const result = await axios.get(API + 'online-users')
  console.log(result);

  if (result.data.users) {
    onlineUsers.value = result.data.users;
  }
}


function showChatPanel(user, emittedMessage = null) {
  if (!user || !user.id) {
    console.error("User is undefined.");
    return;
  }
  const isPanelOpened = chatPanels.panels.find(panel => panel.selectedUser.id === user.id);

  if (!isPanelOpened) {
    const userPanel = {
      selectedUser: user,
      emittedMessage
    };

    selectedUser.value = user; // Set the selected user
    isChatOpen.value = true; // Open the chat pane

                chatPanels.panels.push(userPanel);
                
                getMessages(); // Fetch messages for the selected user immediately after opening the chat panel


    return true;
  }
  // if the panel already opened
  const index = chatPanels.panels.findIndex(panel => panel.selectedUser.id === user.id);
  chatPanels.panels[index] = { ...chatPanels.panels[index], emittedMessage };

  selectedUser.value = user;
  isChatOpen.value = true;

  getMessages();

  console.log(chatPanels.panels);

  return false;
}


function hideChatPanel(user) {
  const filtered = [...chatPanels.panels].filter(panel => panel.selectedUser.id !== user.id);

  chatPanels.panels = [...filtered];
}

function updateSelectedUser(selectedUser) {
  const userIndex = onlineUsers.value.findIndex(u => u.id === selectedUser.id);

  if (userIndex !== -1) {
    onlineUsers.value[userIndex].unseen_messages = [];
  }
}

function handleUserClick(user) {
  showChatPanel(user);

  updateSelectedUser(user);


}

const displayToastMessage = (message) => {
  toast(message, {
    autoClose: 3000,
    type: "info",
    position: "top-left"

  });
}


function submitMessage() {
  if (!selectedUser.value || !messageContent.value) {
    return;
  }

  const payload = {
    receiver_id: selectedUser.value.id,
    message_content: messageContent.value
  };

  axios.post(`${API}messages`, payload)
    .then(response => {
      if (response && response.data.status) {

        messageContent.value = "";

        getMessages()

        scrollToChatBottom();
      }
    }).catch(error => {
      console.error(error.response);
    });
}

async function getMessages() {
  if (!selectedUser.value) {
    console.error("Selected user is undefined.");
    return;
  }
  const result = await axios.get(`${API}messages?receiver_id=${selectedUser.value.id}`)

  if(result.data.messages) {
    userMessages.value = result.data.messages.reverse().map(message => {
      return {
        ...message,
        formattedTime: formatMessageTime(message.created_at)
      };
    });
    console.log(userMessages.value); 

    scrollToChatBottom();
  }
}

function formatMessageTime(isoString) {
  const date = new Date(isoString);
  return date.toLocaleString([], { month: '2-digit', day: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' });
}


const sendMessageUpdateRequest = (messageId) => {
  axios.put(`${API}messages/${messageId}`)
    .then(response => {
      if (response.data.status) {
        console.log('message updated')
      }
    });
}

        const filteredUsers = computed(() => {
            return onlineUsers.value.filter(user => 
                user.name.toLowerCase().includes(searchQuery.value.toLowerCase()) && user.mode !== 'invisible'
            );
        });


        

        function scrollToChatBottom(){
            // wait for the DOM to update
            nextTick(() => {
                if(chatContentRef.value){
                    chatContentRef.value.scrollTop = chatContentRef.value.scrollHeight;

                    scrollPoint.value = chatContentRef.value.scrollTop;
                }
            });
        }
        
                

        async function logout(){
            let confirmLogOut = confirm('Are you sure you want to log out?')

  if (confirmLogOut) {
    const logOutUser = await axios.post(API + 'auth/logout')
    router.push({ path: '/' })
  }
}

function fileLink(file) {

  return new URL(`/src/assets/${file}`, import.meta.url).href;
}

        
        async function navigateToProfile() {
            router.push({ name: 'userProfile' });
        }

        function triggerFileInput() {
            if (fileInput.value) {
                fileInput.value.click();
            }
        }

        function triggerDocumentInput() {
            if (documentInput.value) {
                documentInput.value.click();
            }
        }

        async function sendMediaMessage(event) {
            const files = event.target.files;
            if (files.length > 0) {
                const formData = new FormData();
                formData.append('file', files[0]);
                formData.append('receiver_id', selectedUser.value.id); // Include the receiver_id in the formData

                
                try {
                    const response = await axios.post(`${API}messages/upload`, formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    });
                    
                    getMessages(); 
                    scrollToChatBottom();
                } catch (error) {
                    console.error('Error uploading file:', error);
                }
            }
            event.target.value = ""; // Reset the file input after handling
        }

        async function sendDocumentMessage(event) {
            const files = event.target.files;
            if (files.length > 0) {
                const formData = new FormData();
                formData.append('document', files[0]);
                formData.append('receiver_id', selectedUser.value.id); // Include the receiver_id in the formData

                try {
                    const response = await axios.post(`${API}messages/upload-document`, formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    });
                    
                    getMessages(); 
                    scrollToChatBottom();
                } catch (error) {
                    console.error('Error uploading document:', error);
                }
            }
            event.target.value = ""; // Reset the file input after handling
        }

        function isImageUrl(message) {
            return /\.(jpg|jpeg|png|gif)$/.test(message);
        }

        function isDocumentUrl(message) {
            try {
                const parsedMessage = JSON.parse(message);
                return /\.(pdf|doc|docx|txt)$/.test(parsedMessage.url);
            } catch (error) {
                return false;
            }
        }

        function showLargeImage(url) {
            selectedImageUrl.value = url;
        }

        function getDocumentName(message) {
            try {
                const { originalFileName } = JSON.parse(message);
                return originalFileName;
            } catch (error) {
                return "Unknown Document";
            }
        }
  
        function getDocumentUrl(message) {
            try {
                const { url } = JSON.parse(message);
                return `http://localhost:8000${url.replace(/\\/g, '/')}`;
            } catch (error) {
                return '';
            }
        }


        const handleChatScroll = _.debounce((e) => {
          if (e.target.scrollTop - 100 < scrollPoint.value) {
            const oldMessage = userMessages.value[0];

            axios.get(`${API}messages?receiver_id=${selectedUser.value.id}&earlier_date=${oldMessage.created_at}`)
                .then(response => {
                  if (response && response.data.messages){
                    const filtered = [];

                    response.data.messages.reverse().forEach(message => {
                      if (!userMessages.value.find(m => m.id == message.id)){
                        filtered.push(message);
                      }
                    })
                    userMessages.value = [...filtered, ...userMessages.value];
                  }
                }).catch(error => {
                  console.error(error.response);
                })

        }

        scrollPoint.value = e.target.scrollTop;
    }, 1000);

        watch(() => emittedMessage, (newMessage, oldMessage) => {
          if (newMessage) {
            const isMessageExist = userMessages.value.find(m => m.id == newMessage.id);

            if (!isMessageExist) {
              userMessages.value.push(newMessage);
              scrollToChatBottom();
            }
          }
        })

        // For page first load component mount
        onMounted(() => {

            getUser(userId)
            getOnlineUsers()
            
           window.Echo.private(`chatroom.${userId}`)
                .listen('.message-sent', (e) => {
                    const message = e.message;
                    const senderName = e.user.name;
                    const toastMessage = `New message from ${senderName}: ${message.message}`;
                    console.log("Received message: ", e);

                    if (selectedUser.value && selectedUser.value.id === e.message.sender_id && isChatOpen.value) {
                        // Add the message to the chat without automatically opening the chat panel
                        userMessages.value.push(message);
                        scrollToChatBottom();
                    } else {
                        // Check if neither the sender nor the receiver is in 'do not disturb' mode before showing the toast
                        if (currentUserMode.value.toLowerCase() !== 'do not disturb') {
                            displayToastMessage(toastMessage);
                        }
                        //***current problem is need to refresh the page again in order to have the mode behaviour
                    }
                });
                    

})


</script>
