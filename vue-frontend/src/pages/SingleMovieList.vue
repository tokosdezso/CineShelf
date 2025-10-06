<script setup>
import { onMounted, ref, onUnmounted } from 'vue';
import axiosClient from '../axios'
import { useRoute } from 'vue-router'
import MovieGrideElement from '../components/movie/MovieGrideElement.vue';
import router from '../router.js';
import Filters from '../components/Filters.vue';
import Pagination from '../components/Pagination.vue';
import MovieListElement from '../components/movie/MovieListElement.vue';

const route = useRoute()
const movieList = ref([]);
const filters = ref({
  with_genres: '',
  vote_average_gte: '',
  vote_average_lte: '',
  release_date_gte: '',
  release_date_lte: '',
  sort_by: '',
  page: 1,
})
const errorMessage = ref('')
const pagination = ref({
  page: 1,
  total_pages: 1,
  total_results: 0,
  per_page: 20,
});
const girdView = ref(true);

// fetch single movie list
onMounted(() => {
  // add window resize listener
  window.addEventListener('resize', handleResize);
  // search movie list
  search();
});

// filter movie list
function performFiltering(selectedFilters) {
  filters.value = selectedFilters;
  search();
}

function performPagination(page) {
  filters.value.page = page;
  search();
}

function search() {
  axiosClient.get(`/api/movie-lists/${route.params.id}`, {
    params: filters.value
  })
    .then(response => {
      movieList.value = response.data;
      pagination.value.page = response.data.movies.current_page !== 0 ? response.data.movies.current_page : 1;
      pagination.value.total_pages = response.data.movies.last_page;
      pagination.value.total_results = response.data.movies.total;
      pagination.value.per_page = response.data.movies.per_page;
    })
    .catch(error => {
      console.log(error);
      if (error.response.status === 404 || error.response.status === 403) {
        errorMessage.value = error.response.data.message;
      }
    });
}

// delete movie list
function deleteMovieList(id) {
  if (confirm('Are you sure you want to delete this list?')) {
    axiosClient.delete(`/api/movie-lists/${id}`)
      .then(() => {
        alert('The list has been deleted.');
        // Redirect to MyLists page after deletion
        router.push({ name: 'MyLists' });
      })
      .catch(error => {
        console.log(error);
        if (error.response.status === 404) {
          errorMessage.value = error.response.data.message;
        }
      });
  }
}

// restore movie list
function restore(id) {
  axiosClient.put(`/api/movie-lists/${id}`)
    .then(() => {
      alert('The list has been restored.');
      // Refresh the current page to show the restored list
      router.go(0);
    })
    .catch(error => {
      console.log(error);
      if (error.response.status === 404) {
        errorMessage.value = error.response.data.message;
      }
    });
}

// remove window resize listener
onUnmounted(() => {
  window.removeEventListener('resize', handleResize)
});

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
      <h1 class="text-3xl font-bold tracking-tight text-gray-200">My Lists</h1>
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
        <div v-else>
          <div v-if="!movieList.deleted_at" >
            <div class="flex space-between items-center justify-between">
              <h3 class="text-3xl font-bold tracking-tight text-gray-200 py-5 mr-2">{{movieList.name}}</h3>
            </div>
            <div class="my-4 py-2 rounded text-gray-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
              <p class="text-lg text-gray-200">
                There {{ movieList.movies?.total > 1 ? 'are' : 'is' }} {{ movieList.movies?.total || 0 }} movie{{ movieList.movies?.total > 1 ? 's' : null}} in this filtered list.
              </p>
              <button type="button"
                @click="deleteMovieList(movieList.id)"
                class="rounded-md bg-red-600 px-3.5 py-2.5 text-sm font-semibold text-gray-200 shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                Delete
              </button>
            </div>
            <Filters @apply-filters="performFiltering" />
            <div class="flex items-center justify-center py-5">
              <button @click="toggleView" class="hidden sm:block px-4 py-2 bg-indigo-600 text-gray-100 rounded hover:bg-indigo-500">
                <span v-if="girdView" class="text-sm font-medium text-gray-100">List View</span>
                <span v-else class="text-sm font-medium text-gray-100">Grid View</span>
              </button>
            </div>
            <div v-if="girdView" class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 mt-4">
              <div v-for="movie in movieList.movies?.data" :key="movie.id" class="bg-gray-200 overflow-hidden shadow rounded-lg">
                <MovieGrideElement :movieListId="movieList.id" :movie="movie" />
              </div>
            </div>
            <div v-else class="mt-4">
              <div v-for="movie in movieList.movies?.data" :key="movie.id" class="bg-gray-200 overflow-hidden shadow rounded-lg my-4">
                <MovieListElement :movieListId="movieList.id" :movie="movie" />
              </div>
            </div>
            <Pagination @paginate="performPagination" :pagination="pagination"/>
          </div>
          <div v-else >
            <div class="flex items-center justify-center py-5">
              <h3 class="text-3xl font-bold tracking-tight text-gray-200 py-5">{{movieList.name}}</h3>
            </div>
            <div class="my-4 py-2 px-3 rounded text-gray-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
              <p class="text-lg text-gray-200">
                This list has been deleted. You are able to restore it.
              </p>
              <button 
                type="button"
                @click="restore(movieList.id)"
                class="rounded-md bg-indigo-700 px-3.5 py-2.5 text-sm font-semibold text-gray-200 shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                Restore the list
              </button>
            </div>
            <div class="flex items-center justify-center py-5">
            </div>
          </div>
        </div>
        <div class="flex items-center justify-center py-5">
            <button @click="$router.push({ name: 'MyLists' })"
              type="button"
              class="rounded-md bg-indigo-700 px-3.5 py-2.5 text-sm font-semibold text-gray-200 shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
              Back to lists
            </button>
        </div>
      </div>
    </div>
  </main>
</template>

<style scoped>
</style>