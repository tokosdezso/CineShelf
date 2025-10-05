<script setup>
import { onMounted, ref } from 'vue';
import MovieGrideElement from '../components/movie/MovieGrideElement.vue';
import axiosClient from '../axios'
import SearchBar from '../components/SearchBar.vue';
import Pagination from '../components/Pagination.vue';

const movies = ref([]);
const popular = ref(true);

const pagination = ref({
  page: 1,
  total_pages: 1,
  total_results: 0
});

const filters = ref({
  query: '',
  page: 1,
});

// fetch popular movies on page load
onMounted(() => {
  axiosClient.get('/api/popular-movies')
    .then(response => {
      movies.value = response.data;
    })
    .catch(error => {
      console.log(error);
    });
});

// Search movies requested from search bar
function performSearch(query) {
  filters.value.query = query;
  search();
}

// Search movies requested from pagination
function performPagination(page) {
  console.log(page);
  filters.value.page = page;
  search();
}

// Search movies from TMDB
function search() {
  axiosClient.get('/api/tmdb-movies', {
    params: filters.value
  })
    .then(response => {
      console.log(response.data);
      movies.value = response.data.results;
      pagination.value.page = response.data.page !== 0 ? response.data.page : 1;
      pagination.value.total_pages = response.data.total_pages;
      pagination.value.total_results = response.data.total_results;
      popular.value = false;
      scrollToTop();
    })
    .catch(error => {
      console.log(error);
    });
}

// scroll to top
function scrollToTop() {
  console.log('scrolling to top');
  console.log('animation scrolling to top');
  window.scrollTo({
    top: 0,
    behavior: 'smooth',
  });
}

</script>

<template>
  <header class="relative bg-gray-800 after:pointer-events-none after:absolute after:inset-x-0 after:inset-y-0 after:border-y after:border-white/10">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
      <h1 class="text-3xl font-bold tracking-tight text-white">Movies</h1>
    </div>
  </header>
  <main>
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
      <SearchBar @search="performSearch"/>
      <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
        <div v-for="movie in movies" :key="popular ? `popular-${movie.id}` : `search-${movie.tmdb_id}`" class="bg-gray-200 overflow-hidden shadow rounded-lg">
          <MovieGrideElement v-if="popular" :rank="movie.id" :movie="movie.movie" />
          <MovieGrideElement v-else :movie="movie" />
        </div>
      </div>
    </div>
    <Pagination v-if="!popular" @paginate="performPagination" :pagination="pagination"/>
  </main>
</template>

<style scoped>
</style>