<script setup>
import { onMounted, ref } from 'vue';
import axiosClient from '../axios'
import { useRoute } from 'vue-router'
import MovieGrideElement from '../components/MovieGrideElement.vue';

const route = useRoute()
const movieList = ref([]);
const errorMessage = ref('')

onMounted(() => {
  axiosClient.get(`/api/movie-lists/${route.params.id}`)
    .then(response => {
      console.log(response.data);
      movieList.value = response.data;
    })
    .catch(error => {
      console.log(error);
      if (error.response.status === 404) {
        errorMessage.value = error.response.data.message;
      }
    });
});

</script>

<template>
  <header class="relative bg-gray-800 after:pointer-events-none after:absolute after:inset-x-0 after:inset-y-0 after:border-y after:border-white/10">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
      <h1 class="text-3xl font-bold tracking-tight text-gray-200">My Lists</h1>
    </div>
  </header>
  <main>
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
      <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <div v-if="errorMessage" class="mt-4 py-2 px-3 rounded text-white bg-red-500 ">
          <p class="text-lg font-semibold leading-6 text-gray-200 flex items-center justify-center">
            {{errorMessage}}
          </p>
        </div>
        <div v-else-if="!movieList.deleted_at" >
          <div class="flex space-between items-center justify-between">
            <h3 class="text-3xl font-bold tracking-tight text-gray-200 py-5 mr-2">{{movieList.name}}</h3>
          </div>
          <div class="my-4 py-2 rounded text-gray-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
            <p class="text-lg text-gray-200">
              There {{ movieList.movies?.length > 1 ? 'are' : 'is' }} {{ movieList.movies?.length || 0 }} movie{{ movieList.movies?.length > 1 ? 's' : null}} in this list.
            </p>
            <button type="button" 
              class="rounded-md bg-red-600 px-3.5 py-2.5 text-sm font-semibold text-gray-200 shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
              Delete
            </button>
          </div>
          <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
            <div v-for="movie in movieList.movies" :key="movie.id" class="bg-gray-200 overflow-hidden shadow rounded-lg">
              <MovieGrideElement :movie="movie" />
            </div>
          </div>
        </div>
        <div v-else >
          <div class="flex items-center justify-center py-5">
            <h3 class="text-3xl font-bold tracking-tight text-gray-200 py-5">{{movieList.name}}</h3>
          </div>
          <div class="my-4 py-2 px-3 rounded text-gray-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
            <p class="text-lg text-gray-200">
              This list has been deleted. You are able to restore it.
            </p>
            <button 
              type="button"
              class="rounded-md bg-indigo-700 px-3.5 py-2.5 text-sm font-semibold text-gray-200 shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
              Restore the list
            </button>
          </div>
          <div class="flex items-center justify-center py-5">
          </div>
        </div>
        <div class="flex items-center justify-center py-5">
            <button @click="$router.push({ name: 'MyLists' })"
              type="button"
              class="rounded-md bg-indigo-700 px-3.5 py-2.5 text-sm font-semibold text-gray-200 shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
              Back to lists
            </button>
        </div>
      </div>
    </div>
  </main>
</template>

<style scoped>
</style>