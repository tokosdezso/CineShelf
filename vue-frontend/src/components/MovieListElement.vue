<script setup>
import { ListBulletIcon } from '@heroicons/vue/24/solid';

defineProps({
  movieList: {
    type: Object,
    required: true
  },
})
</script>

<template>
  <li @click="$router.push({ name: 'SingleMovieList', params: { id: movieList.id } })" 
    :key="movieList.id" 
    class="bg-gray-200 overflow-hidden shadow rounded-lg flex justify-between gap-x-6 py-5">
    <div class="flex min-w-0 gap-x-4 items-center px-4">
      <ListBulletIcon class="h-6 w-6 flex-none text-gray-600" />
      <div class="min-w-0 flex px-1 items-center justify-center">
        <h3 class="text-lg font-semibold text-gray-900">{{ movieList.name }}</h3>
      </div>
    </div>
    <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end px-5">
      <p class="text-sm/6 text-gray-900">{{ movieList.movies.length }} movies</p>
      <p v-if="movieList.movies.length > 0" class="mt-1 text-xs/5">
        <span class="flex items-center justify-center text-sm font-medium text-gray-600">Titles: {{ (movieList.movies.map((movie, index) => index < 3 ? movie.title : null)).filter(Boolean).join(', ') }}, ...</span>
      </p>
    </div>
  </li>
</template>