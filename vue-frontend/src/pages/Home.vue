<script setup>
import MovieGrideElement from '../components/MovieGrideElement.vue';
</script>

<template>
  <header class="relative bg-gray-800 after:pointer-events-none after:absolute after:inset-x-0 after:inset-y-0 after:border-y after:border-white/10">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
      <h1 class="text-3xl font-bold tracking-tight text-white">Movies</h1>
    </div>
  </header>
  <main>
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
        <div v-for="movie in movies" :key="movie.id" class="bg-gray-200 overflow-hidden shadow rounded-lg">
          <MovieGrideElement :movie="movie" />
        </div>
      </div>
    </div>
  </main>
</template>

<style scoped>
</style>