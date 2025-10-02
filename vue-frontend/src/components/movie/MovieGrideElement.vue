<template>
  <div class="relative min-h-full py-1">
    <span v-if="rank" class="absolute top-2 left-2 bg-indigo-600 text-gray-200 px-2 py-1 rounded">
      #{{ rank }}
    </span>
    <button 
      v-if="movieListId"
      @click="removeMovie(movieListId, movie.id)"
      class="absolute top-2 right-2 text-red-500 hover:text-red-600"
      title="Delete">
      <TrashIcon class="w-6 h-6" />
    </button>
    <button 
      v-else
      @click="openAddModal(movie.id, movie.title)"
      class="absolute top-2 right-2 text-indigo-500 hover:text-indigo-600 rounded-full border border-indigo-500 hover:border-indigo-600 p-1"
      title="Add">
      <PlusIcon class="w-6 h-6" />
    </button>
    <img :src="movie.poster_path" alt="Image" class="w-full h-48 object-contain">
    <div class="px-4 py-4">
      <h3 class="text-lg font-semibold text-gray-900">{{ movie.title }}</h3>
      <p class="text-sm text-gray-500 mb-4">
        {{ movie.overview.length > 120 ? movie.overview.slice(0, 120) + '...' : movie.overview }}
      </p>
      <div class="flex flex-wrap gap-4">
        <span class="flex items-center justify-center text-sm font-medium text-indigo-600">Rating: {{ movie.vote_average }} <StarSolid class="w-4 h-4 text-yellow-400" /></span>
        <span class="flex items-center justify-center text-sm font-medium text-gray-900">Release: {{ movie.release_date }}</span>
        <span class="flex items-center justify-center text-sm font-medium text-gray-600">Popularity: {{ movie.popularity }}</span>
        <span class="flex items-center justify-center text-sm font-medium text-gray-900">Runtime: {{ movie.runtime ?? '-' }} min</span>
        <span class="flex items-center justify-center text-sm font-medium text-gray-900">Language: {{ movie.language }}</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { StarIcon as StarSolid } from '@heroicons/vue/24/solid'
import router from '../../router.js';
import { TrashIcon, PlusIcon } from '@heroicons/vue/24/outline'
import axiosClient from '../../axios';
import { useAddMovieModalStore } from '../../stores/modal.js'

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

// remove movie from the list
function removeMovie(movieListId, movieId) {
  axiosClient.put(`/api/movie-lists/${movieListId}`, { remove_movie_id: movieId })
    .then(() => {
      alert('The list has been updated.');
      // Refresh the current page to show the restored list
      router.go(0);
    })
    .catch(error => {
      console.log(error);
      alert(error.response.data.message);
    });
}

const modalStore = useAddMovieModalStore()
/**
 * Open the add to movie list modal
 */
function openAddModal(movieId, movieTitle) {
  modalStore.open(movieId, movieTitle)
}
</script>