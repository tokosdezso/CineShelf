<script setup>
import { onMounted, ref, onUnmounted, inject } from 'vue';
import MovieGrideElement from '../components/movie/MovieGrideElement.vue';
import axiosClient from '../axios'
import SearchBar from '../components/SearchBar.vue';
import Pagination from '../components/Pagination.vue';
import MovieListElement from '../components/movie/MovieListElement.vue';

const movies = ref([]);
const popular = ref(true);
const girdView = ref(true);

const pagination = ref({
  page: 1,
  total_pages: 1,
  total_results: 0
});

const filters = ref({
  query: '',
  page: 1,
});

const triggerToast = inject('triggerToast');

// fetch popular movies on page load
onMounted(() => {
  // add window resize listener
  window.addEventListener('resize', handleResize);
  // popular movies
  axiosClient.get('/api/popular-movies')
    .then(response => {
      movies.value = response.data;
    })
    .catch(error => {
      console.log(error);
      triggerToast && triggerToast(error.response?.data?.message || 'Error list the popular movies!', 'error');
    });
});

// remove window resize listener
onUnmounted(() => {
  window.removeEventListener('resize', handleResize)
});

// Search movies requested from search bar
function performSearch(query) {
  filters.value.query = query;
  search();
}

// Search movies requested from pagination
function performPagination(page) {
  filters.value.page = page;
  search();
}

// Search movies from TMDB
function search() {
  axiosClient.get('/api/tmdb-movies', {
    params: filters.value
  })
    .then(response => {
      movies.value = response.data.results;
      pagination.value.page = response.data.page !== 0 ? response.data.page : 1;
      pagination.value.total_pages = response.data.total_pages;
      pagination.value.total_results = response.data.total_results;
      popular.value = false;
      scrollToTop();
    })
    .catch(error => {
      console.log(error);
      triggerToast && triggerToast(error.response?.data?.message || 'Error list the movies!', 'error');
    });
}

// scroll to top
function scrollToTop() {
  window.scrollTo({
    top: 0,
    behavior: 'smooth',
  });
}

// Toggle the view between grid and list
function toggleView() {
  girdView.value = !girdView.value;
}

// handle window resize
function handleResize() {
  girdView.value = (window.innerWidth < 640) ? true : girdView.value;
}

</script>

<template>
  <header class="relative bg-gray-800 after:pointer-events-none after:absolute after:inset-x-0 after:inset-y-0 after:border-y after:border-white/10">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
      <h1 class="text-3xl font-bold tracking-tight text-white">Movies</h1>
    </div>
  </header>
  <main>
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 relative">
      <SearchBar @search="performSearch"/>
      <button v-if="movies.length > 0"
        @click="toggleView"
        class="hidden sm:block px-4 py-2 bg-indigo-600 text-gray-100 rounded hover:bg-indigo-500 absolute top-6 right-6"
      >
        <span v-if="girdView" class="text-sm font-medium text-gray-100">List View</span>
        <span v-else class="text-sm font-medium text-gray-100">Grid View</span>
      </button>
      <div v-if="girdView" class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
        <div v-for="movie in movies" :key="popular ? `grid-popular-${movie.id}` : `grid-search-${movie.tmdb_id}`" class="bg-gray-200 overflow-hidden shadow rounded-lg">
          <MovieGrideElement v-if="popular" :rank="movie.id" :movie="movie.movie" />
          <MovieGrideElement v-else :movie="movie" />
        </div>
      </div>
      <div v-else class="">
        <div v-for="movie in movies" :key="popular ? `list-popular-${movie.id}` : `list-search-${movie.tmdb_id}`" class="bg-gray-200 overflow-hidden shadow rounded-lg my-4">
          <MovieListElement v-if="popular" :rank="movie.id" :movie="movie.movie" />
          <MovieListElement v-else :movie="movie" />
        </div>
      </div>
    </div>
    <Pagination v-if="!popular" @paginate="performPagination" :pagination="pagination"/>
  </main>
</template>

<style scoped>
</style>