<script setup>
import { onMounted, ref, inject } from 'vue';
import axiosClient from '../axios';
import { useRoute } from 'vue-router';
import router from '../router.js';
import { StarIcon as StarSolid, PhotoIcon } from '@heroicons/vue/24/solid';
import { useAddMovieModalStore } from '../stores/modal.js';
import GoBack from '../components/GoBack.vue';

const route = useRoute();
const movie = ref([]);
const errorMessage = ref('');
const modalStore = useAddMovieModalStore();
const triggerToast = inject('triggerToast');

// fetch single movie list
onMounted(() => {
  axiosClient.get(`/api/tmdb-movies/${route.params.id}`)
    .then(response => {
      movie.value = response.data;
    })
    .catch(error => {
      console.log(error);
      if (error.response.status === 404 || error.response.status === 403) {
        console.log(error.response.data.message);
        errorMessage.value = error.response.data.message;
      }
      triggerToast && triggerToast(error.response?.data?.message || 'Error creating list!', 'error');
    });
});

// Open the add to movie list modal
function openAddModal(movie) {
  if (!movie.id) {
    axiosClient.post('/api/tmdb-movies', { tmdb_id: movie.tmdb_id })
      .then(response => {
        modalStore.open(response.data.id, response.data.title)
      })
      .catch(error => {
        console.log(error);
        triggerToast && triggerToast(error.response?.data?.message || 'Error creating list!', 'error');
      });
  } else {
    modalStore.open(movie.id, movie.title)    
  }
}

</script>

<template>
  <header class="relative bg-gray-800 after:pointer-events-none after:absolute after:inset-x-0 after:inset-y-0 after:border-y after:border-white/10">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
      <h1 class="text-3xl font-bold tracking-tight text-gray-200">Movie</h1>
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
        <div v-else class="mx-auto max-w-2xl px-4 pt-10 pb-16 sm:px-6 lg:grid lg:max-w-7xl lg:grid-cols-3 lg:grid-rows-[auto_auto_1fr] lg:gap-x-8 lg:px-8 lg:pt-16 lg:pb-24">
          <div class="lg:col-span-2 lg:border-r lg:border-gray-200 lg:pr-8">
            <h1 class="text-3xl font-bold tracking-tight text-gray-100 sm:text-3xl">{{movie.title}}</h1>
          </div>
          <div class="pt-12 lg:row-span-3 lg:mt-0 relative">
            <p class="text-m py-2 tracking-tight text-gray-100">{{ movie.overview }}</p>
            <span class="flex py-2 text-sm font-medium text-gray-100">Genres: {{ movie.genres?.map((genre) => genre.name).join(', ') }}</span>
            <span class="flex py-2 text-sm font-medium text-indigo-300">Rating: {{ movie.vote_average }} <StarSolid class="w-4 h-4 text-yellow-400" /></span>
            <span class="flex py-2 text-sm font-medium text-gray-100">Release: {{ movie.release_date }}</span>
            <span class="flex py-2 text-sm font-medium text-gray-100">Popularity: {{ movie.popularity }}</span>
            <span class="flex py-2 text-sm font-medium text-gray-100">Runtime: {{ movie.runtime ?? '-' }} min</span>
            <span class="flex py-2 text-sm font-medium text-gray-100">Language: {{ movie.language }}</span>
            <button @click="openAddModal(movie)" 
              class="mt-4 flex items-center justify-center rounded-full border border-indigo-600 bg-indigo-500 px-4 py-2 text-sm font-medium text-gray-100 hover:bg-indigo-600 absolute right-0 bottom-0"
            >
              Add to List
            </button>
          </div>
          
          <div class="py-10 lg:col-span-2 lg:col-start-1 lg:border-r lg:border-gray-200 lg:pt-6 lg:pr-8 lg:pb-16">
            <div class="">
              <img v-if="movie.poster_path && !movie.poster_path.includes('/default.jpg')" class="w-full" :src="movie.poster_path" :alt="movie.title" />
              <PhotoIcon v-else class="w-32 h-58 text-gray-200 mx-auto" />
            </div>
          </div>
        </div>
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 flex justify-center">
          <GoBack backgroundColor="bg-indigo-500" textColor="text-gray-100"/>
        </div>
      </div>
    </div>
  </main>
</template>

<style scoped>
</style>