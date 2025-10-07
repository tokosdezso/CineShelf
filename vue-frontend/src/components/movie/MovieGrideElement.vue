<template>
  <div class="relative min-h-full py-1">
    <span v-if="rank" class="absolute top-2 left-2 bg-indigo-600 text-gray-200 px-2 py-1 rounded">
      #{{ rank }}
    </span>
    <button 
      v-if="movieListId"
      @click="removeMovie(movieListId, movie.id)"
      class="absolute top-2 right-2 text-red-500 hover:text-red-600"
      title="Delete"
    >
      <TrashIcon class="w-6 h-6" />
    </button>
    <button 
      v-else
      @click="openAddModal(movie)"
      class="absolute top-2 right-2 text-indigo-500 hover:text-indigo-600 rounded-full border border-indigo-500 hover:border-indigo-600 p-1"
      title="Add"
    >
      <PlusIcon class="w-6 h-6" />
    </button>
    <img v-if="movie.poster_path && !movie.poster_path.includes('/default.jpg')" :src="movie.poster_path" alt="Image" class="w-full h-48 object-contain">
    <PhotoIcon v-else class="w-full h-48 text-gray-900 mx-auto" />
    <div class="px-4 py-4">
      <h3 @click="$router.push({ name: 'SingleMovie', params: { id: movie.tmdb_id } })"
        class="text-lg font-semibold text-gray-900">
        {{ movie.title }}
      </h3>
      <p class="text-sm text-gray-500 mb-4">
        {{ movie.overview.length > 120 ? movie.overview.slice(0, 120) + '...' : movie.overview }}
      </p>
      <div class="flex flex-row flex-wrap flex-around gap-4">
        <div class="flex flex-col text-sm font-medium">
          <span class="flex text-sm font-medium text-indigo-600">Rating: </span>
          <span class="flex text-sm font-medium text-gray-900">Release: </span>
          <span class="flex text-sm font-medium text-gray-900">Popularity: </span>
          <span class="flex text-sm font-medium text-gray-900">Runtime: </span>
          <span class="flex text-sm font-medium text-gray-900">Language: </span>
        </div>
        <div class="flex flex-col text-sm font-medium">
          <span class="flex text-sm font-medium text-indigo-600">{{ movie.vote_average }} <StarSolid class="w-4 h-4 ml-1 text-yellow-500" /></span>
          <span class="flex text-sm font-medium text-gray-900">{{ movie.release_date }}</span>
          <span class="flex text-sm font-medium text-gray-600">{{ movie.popularity }}</span>
          <span class="flex text-sm font-medium text-gray-900">{{ movie.runtime ?? '-' }} min</span>
          <span class="flex text-sm font-medium text-gray-900">{{ movie.language }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { StarIcon as StarSolid } from '@heroicons/vue/24/solid'
import router from '../../router.js';
import { TrashIcon, PlusIcon, PhotoIcon } from '@heroicons/vue/24/outline'
import axiosClient from '../../axios';
import { useAddMovieModalStore } from '../../stores/modal.js'
import { inject } from 'vue';

defineProps({
  movie: {
    type: Object,
    required: true
  },
  rank: {
    type: Number,
    required: false
  },
  movieListId: {
    type: Number,
    required: false
  },
});

const triggerToast = inject('triggerToast');

// remove movie from the list
function removeMovie(movieListId, movieId) {
  axiosClient.put(`/api/movie-lists/${movieListId}`, { remove_movie_id: movieId })
    .then(() => {
      triggerToast && triggerToast('The list has been updated.', 'success');
      router.go(0);
    })
    .catch(error => {
      console.log(error);
      triggerToast && triggerToast(error.response?.data?.message || 'Error updating list!', 'error');
    });
}

const modalStore = useAddMovieModalStore()
/**
 * Open the add to movie list modal
 */
function openAddModal(movie) {
  if (!movie.id) {
    axiosClient.post('/api/tmdb-movies', { tmdb_id: movie.tmdb_id })
      .then(response => {
        modalStore.open(response.data.id, response.data.title)
      })
      .catch(error => {
        console.log(error);
        triggerToast && triggerToast(error.response?.data?.message || 'Error adding movie!', 'error');
      });
  } else {
    modalStore.open(movie.id, movie.title)    
  }
}
</script>