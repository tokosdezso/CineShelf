<script setup>
import { onMounted, ref, inject} from 'vue';
import axiosClient from '../axios';
import MovieListsElement from '../components/movieList/MovieListElement.vue';
import CreateMovieList from '../components/movieList/CreateMovieList.vue';

const movieLists = ref([]);
const triggerToast = inject('triggerToast');

onMounted(() => {
  axiosClient.get('/api/movie-lists?with_trashed=1')
    .then(response => {
      movieLists.value = response.data;
    })
    .catch(error => {
      console.log(error);
      triggerToast && triggerToast(error.response?.data?.message || 'Error list the movie lists!', 'error');
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