<script setup>
  import HomeLogo from '@/components/HomeLogo.vue';
  
  async function login(){
    await axios.get('/sanctum/csrf-cookie')
    await axios.post('/api/login', {
      username: document.getElementById('username').value,
      password: document.getElementById('password').value
    })
  }
</script>

<template>
  
  <HomeLogo />
  
  <!-- Left: Image -->
  <div class="absolute w-full h-screen hidden lg:block left-0 top-0 -z-10 opacity-10 bg-[url('../assets/HomePageBG.png')]"></div>

  <!-- Right: Login Form -->
  <div class="bg-gray-700 h-screen lg:p-36 md:p-52 sm:20 p-8 w-1/2 right-0 absolute lg:w-1/2 z-10">
    <h1 class="text-2xl font-semibold mb-4">Login</h1>
    <form action="#" method="POST">

      <!-- Username Input -->
      <div class="mb-4 text-gray-600">
        <label for="username" class="block text-gray-600">Username</label>
        <input type="text" id="username" name="username" placeholder="Username" class="placeholder:text-gray-300 w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-blue-500" autocomplete="off">
      </div>

      <!-- Password Input -->
      <div class="mb-4 text-gray-600">
        <label for="password" class="block text-gray-600">Password</label>
        <input type="password" id="password" name="password" placeholder="Password" class="placeholder:text-gray-300 w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-blue-500" autocomplete="off">
      </div>

      <!-- Remember Me Checkbox -->
      <div class="mb-4 flex items-center">
        <input type="checkbox" id="remember" name="remember" class="text-blue-500">
        <label for="remember" class="text-gray-600 ml-2">Remember Me</label>
      </div>

      <!-- Forgot Password Link -->
      <div class="mb-6 text-blue-500">
        <a href="#" class="hover:underline">Forgot Password?</a>
      </div>
      <!-- Login Button -->
      <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-md py-2 px-4 w-full">Login</button>
    </form>
    <!-- Sign up  Link -->
    <div class="mt-6 text-blue-500 text-center">
      <a href="#" class="hover:underline">Sign up Here</a>
    </div>
  </div>
  <!-- </div> -->
</template>
