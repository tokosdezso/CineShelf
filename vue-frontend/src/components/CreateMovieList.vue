<script setup>
import { defineProps, defineEmits } from 'vue';
import { ref } from 'vue';
import axiosClient from '../axios';

const props = defineProps({
  movieLists: Array
});
const emit = defineEmits(['update:movieLists']);
const isCreatingAList = ref(false);
const data = ref({
  name: '',
});


// Create a new movie list.
function createMovieList() {
  axiosClient.post('/api/movie-lists', data.value)
    .then(response => {
      const updatedLists = [...props.movieLists, response.data];
      // Emit the updated movie lists array to the parent component
      emit('update:movieLists', updatedLists);
      isCreatingAList.value = false;
      data.value.name = '';
    })
    .catch(error => {
      console.log(error);
      alert(error.response.data.message);
    });
}

</script>

<template>
  <div>
    <div v-if="!isCreatingAList" class="flex justify-center py-5">
        <button @click="isCreatingAList = true"
          type="button"
          class="rounded-md bg-indigo-700 px-3.5 py-2.5 text-sm font-semibold text-gray-200 shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
          Create a new list
        </button>
    </div>
    <div v-else class="py-5 px-5">
      <h4 class="text-3xl font-bold tracking-tight text-gray-200 py-5 text-center align-items-center">Create a new list</h4>
      <form @submit.prevent="createMovieList" class="flex flex-col">
        <div>
          <label for="name" class="block text-sm font-medium text-gray-200 mb-1">List Name</label>
          <input
            type="text"
            name="name"
            id="name"
            v-model="data.name"
            placeholder="Enter list name"
            class="w-full px-3 py-2 rounded-md bg-gray-600 text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
            required
          />
        </div>
        <button
          type="submit"
          class="mt-2 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-500 focus:ring-2 focus:ring-indigo-400 focus:outline-none"
        >
          Create List
        </button>
      </form>
    </div>
  </div>
</template>