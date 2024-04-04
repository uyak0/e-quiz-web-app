<template>
    <!--Header-->
    <div class="bg-white border b border-gray-300 fixed top-0 w-full shadow">
    <div class="lg:container mx-auto p-4">
      <div class="grid grid-cols-3 gap-4">
        <div class="col-span-1 min-w-[250px]">
          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <img :src="fileLink('UserProfile.png')" class="w-12 h-12 rounded-full border-2 border-blue-400" alt="user">
              <span class="font-semibold text-xl pl-1">{{ userData.name }}</span>
            </div>
            <div class="relative inline-block text-left group">
              <three-dots-icon class="w-6 h-6 cursor-pointer"></three-dots-icon>
              <div class="origin-top-right absolute right-0 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50 hidden group-hover:block">
                <div class="py-1">
                  <a href="" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 cursor-pointer">Profile</a>
                  <a href="" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 cursor-pointer" @click.prevent="logout">Logout</a>
                </div>
              </div>
            </div>
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
                <input type="text" placeholder="Search" class="w-full p-2 rounded-md border border-gray-300 focus:outline-none focus:ring focus:border-blue-400 mb-4">

                <!--List-->
                <div class="max-h-96 overflow-y-auto">
                    <div v-for="user in onlineUsers" :key="user.id" class="flex p-2 items-center mb-3 cursor-pointer rounded-md bg-gray-100" @click="showChatPanel(user)">
                        <div class="relative">
                            <img :src="fileLink('UserProfile.png')" class="w-12 h-12 rounded-full border-2 border-blue-400" :alt="user.name">
                            <div class="absolute h-3 w-3 bg-slate-400 rounded-full -top-1.5 -left-1.5 ml-2"></div>
                        </div>
                        <div class="ml-3">
                            <p class="font-semibold">{{ user.name }}</p>
                            <p class="text-gray-500">OK</p> <!-- Assuming there's a 'status' property in the user object -->
                        </div>
                    </div>
                </div>
            </div>
        

            <div class="col-span-2 bg-stone-50 shadow-md rounded-md">

                <div v-if="isChatOpen">
                    
                    <!--Chat Header-->
                    <div class="flex items-center justify-between mb-4 bg-slate-200 px-4 pt-2 pb-2 rounded-tl-md rounded-tr-md">
                        <div class="flex items-center">
                            <img :src="fileLink('UserProfile.png')" class="w-12 h-12 rounded-full border-2 border-blue-400" alt="User">
                            <div class="ml-3">
                                <p class="font-semibold" >{{ selectedUser.name }}</p>
                                <p class="text-gray-500">last seen 4.15 pm</p>
                            </div>
                        </div>
                        <div class="relative inline-block text-left group">
                           <three-dots-icon class="w-6 h-6 cursor-pointer"></three-dots-icon>

                           <div class="origin-top-right absolute right-0 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50 hidden group-hover:block">
                                <div class="py-1">
                                    <a href="" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 cursor-pointer">Close Chat</a>
                                    <a href="" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 cursor-pointer">Clear Chat</a>
                                </div>
                           </div>
                        </div>
                    </div>

                    <!-- Chat Body -->
                    <div class="overflow-y-auto max-h-64 min-h-[19rem] px-4" ref="chatContentRef">
                        <div v-for="message in userMessages" :key="message.id" >
                            <!--Receiver Message -->
                            <div v-if="message.sender_id != userId" class="flex items-center justify-start mb-4">
                                <img :src="fileLink('UserProfile.png')" class="w-6 h-6 rounded-full border-2 border-blue-400" alt="User">
                                <div class="relative group text-sm p-2 shadow bg-white rounded-md max-w-xs">
                                    {{ message.message }}

                                    <div class="absolute top-1/2 -translate-y-1/2 left-full ml-1 hidden group-hover:block bg-gray-600 py-1 px-1.5 rounded z-50 text-white w-max">12:00</div>
                                </div>
                                <three-dots-icon class="w-4 h-4 cursor-pointer"></three-dots-icon>
                            </div>
                            <!--Sender Message -->
                            <div v-else class="flex items-center justify-end mb-4">
                                <three-dots-icon class="w-4 h-4 cursor-pointer"></three-dots-icon>
                                <div class="relative group text-sm p-2 shadow bg-indigo-100 rounded-md max-w-xs">
                                    {{ message.message }}

                                    <div class="absolute top-1/2 -translate-y-1/2 right-full mr-1 hidden group-hover:block bg-gray-600 py-1 px-1.5 rounded z-50 text-white w-max">12:00</div>
                                </div>
                                <img :src="fileLink('UserProfile.png')" class="w-6 h-6 rounded-full border-2 border-blue-400" alt="User">
                            </div>
                        </div>
                    </div>

                    <!--Chat Footer-->
                    <div class="flex items-center p-4 bg-white rounded-bl-md rounded-br-md">
                        <input type="text" v-model="messageContent" placeholder="Type your message here" class="w-full p-2 rounded-md border border-gray-300 focus:outline-none focus:ring focus:border-blue-400 mb-4">
                        <button @click="submitMessage" class="bg-blue-600 text-white px-4 py-2 rounded-md disabled:bg-gray-400 ml-2" >Send</button>
                    </div>
                </div>
                
                <div v-else class="flex flex-col items-center justify-center min-h-[19rem]">
                    <img :src="fileLink('UserProfile.png')" alt="">
                    <p class="text-2xl font-semibold mt-4">E-Quiz Web App</p>
                    <p class="text-gray-500">Start a conversation</p>
                </div>
                
            </div>
        </div>
    </div>
</template>

<script setup>
import axios from 'axios';
import ThreeDotsIcon from "@/components/icons/ThreeDotsIcon.vue";
import {useRoute, useRouter } from "vue-router";
import { ref, onMounted, provide, reactive} from 'vue'; // Import onMounted to fetch data when the component is mounted


const API = import.meta.env.VITE_LARAVEL_API;

    
    const emits = defineEmits(['onCloseChat'])
    const route = useRoute();
    const router = useRouter();
    const user = ref({});
    const userId = route.params.userId
    const userRole = route.params.userRole
    const messageContent = ref("");
    const userMessages = ref([]);
    const userData = ref({})
    const onlineUsers = ref([]);
    const isChatOpen = ref(false)
    const selectedUser = ref(null)
    const chatContentRef = ref(null);


        const chatPanels = reactive ({panels:[]});

        async function getUser() {
            try {
                const res = await axios.get(`${API}user`, {params: { id: userId }});
                userData.value = res.data
                console.log('User data:', userData.value);
            } catch (error) {
                console.error('Error fetching user:', error);
            }
        }

        async function getOnlineUsers(){
            const result = await axios.get(API + 'online-users')
            console.log(result);

            if(result.data.users) {
                onlineUsers.value = result.data.users;
            }
        }


        function showChatPanel(user, emittedMessage = null) {
            const isPanelOpened = chatPanels.panels.find(panel => panel.selectedUser.id === user.id);

            if(!isPanelOpened) {
                const userPanel = {
                    selectedUser: user,
                    emittedMessage
                };

                selectedUser.value = user; // Set the selected user
                isChatOpen.value = true; // Open the chat pane

                chatPanels.panels.push(userPanel);
                return true;
            }
            // if the panel already opened
            const index = chatPanels.panels.findIndex(panel => panel.selectedUser.id === user.id);
            chatPanels.panels[index] = {...chatPanels.panels[index], emittedMessage};

            selectedUser.value = user; // Set the selected user
            isChatOpen.value = true; // Open the chat pane

            console.log(chatPanels.panels);

            getMessages()

            return false; 
        }


        function hideChatPanel(user){
            const filtered = [...chatPanels.panels].filter(panel => panel.selectedUser.id !== user.id);

            chatPanels.panels = [...filtered];
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

        async function getMessages(){
            const result = await axios.get(`${API}messages?receiver_id=${selectedUser.value.id}`)

            if(result.data.messages) {
                userMessages.value = result.data.messages.reverse();
                console.log(userMessages.value); // Log the userMessages value

                scrollToChatBottom();
            }
        }


        function scrollToChatBottom(){
            //Auto scroll to the chat bottom to show the latest message
            setTimeout(() => {
                if(chatContentRef && chatContentRef.value){
                    chatContentRef.value.scrollTop = chatContentRef.value.scrollHeight;
                }
            }, 300);
        }

        
                

        function logout(){
            console.log('logout');
        }

        function fileLink(file) {
            return '/assets/' + file;
        }

        // Fetch user data when the component is mounted
        onMounted(() => {
            getUser(userId)
            getOnlineUsers()
            showChatPanel
            hideChatPanel
            console.log
        })
    
        
</script>

<style scoped>

</style>