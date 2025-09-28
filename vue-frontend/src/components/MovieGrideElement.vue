<template>
  <div class="min-h-full">
    <img :src="movie.poster_path" alt="Image" class="w-full h-48 object-contain">
    <div class="px-4 py-4">
      <h3 class="text-lg font-semibold text-gray-900">{{ movie.title }}</h3>
      <p class="text-sm text-gray-500 mb-4">{{ movie.overview }}</p>
      <div class="flex flex-wrap gap-4">
        <span class="flex items-center justify-center text-sm font-medium text-indigo-600">Rating: {{ movie.vote_average }} <StarSolid class="w-4 h-4 text-yellow-400" /></span>
        <span class="flex items-center justify-center text-sm font-medium text-gray-900">Release: {{ movie.release_date }}</span>
        <span class="flex items-center justify-center text-sm font-medium text-gray-600">Popularity: {{ movie.popularity }}</span>
        <span class="flex items-center justify-center text-sm font-medium text-gray-900">Runtime: {{ movie.runtime }} min</span>
        <span class="flex items-center justify-center text-sm font-medium text-gray-900">Language: {{ movie.language }}</span>
      </div>
    </div>
  </div>
</template>

<script>
import { StarIcon as StarSolid } from '@heroicons/vue/24/solid'

export default {
  name: 'FilmGrideElement',
  props: {
    movie: {
      type: Object,
      required: true
    }
  }
}
</script>