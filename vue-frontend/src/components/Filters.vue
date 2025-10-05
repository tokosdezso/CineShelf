<script setup>
import { ref, defineEmits, onMounted } from 'vue';
import axiosClient from '../axios';

const genres = ref([]);
const emit = defineEmits(['apply-filters']);

const filters = ref({
  with_genres: '',
  vote_average_gte: '',
  vote_average_lte: '',
  release_date_gte: '',
  release_date_lte: '',
  sort_by: '',
});

// fetch genres
onMounted(() => {
  axiosClient.get('/api/gneres')
    .then(response => {
      genres.value = response.data;
    })
    .catch(error => {
      console.log(error);
    });
});

// clear filters
function clearFilters () {
  filters.value = {
    with_genres: '',
    vote_average_gte: '',
    vote_average_lte: '',
    release_date_gte: '',
    release_date_lte: '',
    sort_by: '',
  };
}

</script>

<template>
  <div class="p-4 my-4 bg-gray-200 rounded-lg ">
    <label>Genre</label>
    <select 
      id="genreSelect"
      v-model="filters.with_genres"
      class="border p-1 rounded w-full mb-2">
      <option value="" class="mt-2 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100">-- Select a list --</option>
      <option v-for="genre in genres" :key="genre.id" :value="genre.id" class="mt-2 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100">
        {{ genre.name }}
      </option>
    </select>
    <label>Vote Average</label>
    <div class="flex gap-2 mb-2">
      <input v-model="filters.vote_average_gte" type="number" step="0.1" placeholder="Min" class="border p-1 rounded w-1/2"/>
      <input v-model="filters.vote_average_lte" type="number" step="0.1" placeholder="Max" class="border p-1 rounded w-1/2"/>
    </div>
    <label>Release Date</label>
    <div class="flex gap-2 mb-2">
      <input v-model="filters.release_date_gte" type="date" class="border p-1 rounded w-1/2"/>
      <input v-model="filters.release_date_lte" type="date" class="border p-1 rounded w-1/2"/>
    </div>
    <label>Sort By</label>
    <select v-model="filters.sort_by" class="border p-1 rounded w-full mb-2">
      <option value="" class="mt-2 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100">-- Select a list --</option>
      <option value="title.asc">Name A-Z</option>
      <option value="title.desc">Name Z-A</option>
      <option value="release_date.desc">Date ↓</option>
      <option value="release_date.asc">Date ↑</option>
      <option value="popularity.desc">Popularity ↓</option>
      <option value="popularity.asc">Popularity ↑</option>
      <option value="vote_average.desc">Rating ↓</option>
      <option value="vote_average.asc">Rating ↑</option>
    </select>
    <div class="flex justify-between flex-wrap">
      <button
        @click="$emit('apply-filters', filters)"
        class="bg-indigo-600 text-white px-4 py-2 mb-1 rounded hover:bg-indigo-500"
      >
        Apply Filters
      </button>
      <button
        @click="clearFilters"
        class="bg-indigo-600 text-white px-4 py-2 mb-1 rounded hover:bg-indigo-500"
      >
        Clear filters
      </button>
    </div>
  </div>
</template>
