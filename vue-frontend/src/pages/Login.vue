<script setup>
import GuestLayout from '../components/layers/GuestLayout.vue';
import axiosClient from '../axios';
import { ref } from 'vue';
import router from '../router.js';

const data = ref({
  email: '',
  password: '',
})

const errorMessage = ref('')

// login form submit
function submit() {
  axiosClient.get('/sanctum/csrf-cookie').then(response => {
    axiosClient.post('/login', data.value)
      .then(response => {
        router.push({name: 'Home'})
      })
      .catch(error => {
        console.log(error.response);
        errorMessage.value = error.response.data.errors;
      });
  });
}
</script>

<template>
  <GuestLayout>
    <h2 class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-white">Sign in to your account</h2>
    <div v-if="errorMessage" class="mt-4 py-2 px-3 rounded text-white bg-red-400">
      {{errorMessage}}
    </div>
    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
      <form @submit.prevent="submit" class="space-y-6">
        <div>
          <label for="email" class="block text-sm/6 font-medium text-gray-100">Email address</label>
          <div class="mt-2">
            <input id="email" 
              v-model="data.email"
              type="email" 
              name="email" 
              required 
              autocomplete="email" 
              class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6" />
          </div>
        </div>
        <div>
          <div class="flex items-center justify-between">
            <label for="password" class="block text-sm/6 font-medium text-gray-100">Password</label>
          </div>
          <div class="mt-2">
            <input id="password"
              v-model="data.password"
              type="password"
              name="password"
              required
              autocomplete="current-password"
              class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6" />
          </div>
        </div>
        <div>
          <button type="submit" class="flex w-full justify-center rounded-md bg-indigo-500 px-3 py-1.5 text-sm/6 font-semibold text-white hover:bg-indigo-400 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">Sign in</button>
        </div>
      </form>
      <p class="mt-10 text-center text-sm/6 text-gray-400">
        Not a member?
        <RouterLink :to="{ name: 'Signup' }" class="font-semibold text-indigo-400 hover:text-indigo-300">
          Create an account
        </RouterLink>
      </p>
    </div>
  </GuestLayout>
</template>

<style scoped>
</style>