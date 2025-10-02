<script setup>
import { onMounted, ref } from 'vue';
import axiosClient from '../axios';
import MovieListsElement from '../components/MovieListElement.vue';
import CreateMovieList from '../components/CreateMovieList.vue';

const movieLists = ref([]);

onMounted(() => {
  axiosClient.get('/api/movie-lists')
    .then(response => {
      movieLists.value = response.data;
    })
    .catch(error => {
      console.log(error);
    });
});

</script>

<template>
    <header class="relative bg-gray-800 after:pointer-events-none after:absolute after:inset-x-0 after:inset-y-0 after:border-y after:border-white/10">
      <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-white">My Lists</h1>
      </div>
    </header>
    <main>
      <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <ul v-for="movieList in movieLists" :key="movieList.id" class="bg-gray-00 overflow-hidden shadow rounded-lg py-5">
          <MovieListsElement :movieList="movieList" />
        </ul>
        <CreateMovieList :movieLists="movieLists" @update:movieLists="movieLists = $event"/>
      </div>
    </main>
</template>

<style scoped>
</style>