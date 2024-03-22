<template>
    <!--Header-->
    <div class="bg-white border b border-gray-300 fixed top-0 w-full shadow">
        <div class="lg:container mx-auto p-4">
            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-1 min-w-[250px]">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <img :src="fileLink('UserProfile.png')" class="w-12 h-12 rounded-full border-2 border-blue-400" alt="user">
                            <span class="font-semibold text-xl pl-1">Jee Sheng</span>
                        </div>
                        <div class="relative inline-block text-left group">
                           <three-dots-icon class="w-6 h-6 cursor-pointer"></three-dots-icon>

                           <div class="origin-top-right absolute right-0 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50 hidden group-hover:block">
                                <div class="py-1">
                                    <a href="" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 cursor-pointer">Profile</a>
                                    <a href="" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 cursor-pointer">Logout</a>
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
                    <div class="flex p-2 items-center mb-3 cursor-pointer rounded-md bg-gray-100">
                        <div class="relative">
                            <img :src="fileLink('UserProfile.png')" class="w-12 h-12 rounded-full border-2 border-blue-400" alt="User">
                            <div class="absolute h-3 w-3 bg-slate-400 rounded-full -top-1.5 -left-1.5 ml-2"></div>
                        </div>
                        <div class="ml-3">
                            <p class="font-semibold">Jee Sheng</p>
                            <p class="text-gray-500">Ello</p>
                        </div>
                    </div>
                    <div class="flex p-2 items-center mb-3 cursor-pointer rounded-md hover:bg-gray-100">
                        <div class="relative">
                            <img :src="fileLink('UserProfile.png')" class="w-12 h-12 rounded-full border-2 border-blue-400" alt="User">
                            <div class="absolute h-3 w-3 bg-green-600 rounded-full -top-1.5 -left-1.5 ml-2"></div>
                        </div>
                        <div class="ml-3">
                            <p class="font-semibold">Jee Sheng</p>
                            <p class="text-gray-500">Oii</p>
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
                                <p class="font-semibold">Jee Sheng</p>
                                <p class="text-gray-500">Ello</p>
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

                    <!--Chat Body-->
                    <div class="overflow-y-auto max-h-64 min-h-[19rem] px-4">
                        <div class="flex items-center mb-4">
                            <img :src="fileLink('UserProfile.png')" class="w-6 h-6 rounded-full border-2 border-blue-400" alt="User">
                            <div class="relative group text-sm p-2 shadow bg-white rounded-md max-w-xs">
                                lorem ipsum dolor sit amet

                                <div class="absolute top-1/2 -translate-y-1/2 left-full ml-1 hidden group-hover:block bg-gray-600 py-1 px-1.5 rounded z-50 text-white w-max">12:00</div>
                            </div>
                            <three-dots-icon class="w-4 h-4 cursor-pointer"></three-dots-icon>
                        </div>
                        <div class="flex items-center justify-end mb-4">
                            <three-dots-icon class="w-4 h-4 cursor-pointer"></three-dots-icon>
                            <div class="relative group text-sm p-2 shadow bg-indigo-100 rounded-md max-w-xs">
                                lorem ipsum dolor sit amet

                                <div class="absolute top-1/2 -translate-y-1/2 right-full mr-1 hidden group-hover:block bg-gray-600 py-1 px-1.5 rounded z-50 text-white w-max">12:00</div>
                            </div>
                            <img :src="fileLink('UserProfile.png')" class="w-6 h-6 rounded-full border-2 border-blue-400" alt="User">
                        </div>
                    </div>

                    <!--Chat Footer-->
                    <div class="flex items-center p-4 bg-white rounded-bl-md rounded-br-md">
                        <input type="text" placeholder="Search" class="w-full p-2 rounded-md border border-gray-300 focus:outline-none focus:ring focus:border-blue-400 mb-4">
                        <button class="bg-blue-600 text-white px-4 py-2 rounded-md disabled:bg-gray-400 ml-2" disabled>Send</button>
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

<script>
import {ThreeDotsIcon} from "@/components/ThreeDotsIcon.vue";


export default {
    name: 'ChatApp',
    components:  { ThreeDotsIcon },
    data(){
        return{
            isChatOpen:true,
        }
    },
    methods: {
        logout(){
            console.log('logout');
        },

        fileLink(file) {
            return '/assets/' + file;
        }
    }
}

</script>

<style scoped>

</style>