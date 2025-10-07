<!-- components/AddToListModal.vue -->
<template>
  <div class="fixed inset-0 backdrop-blur-sm bg-opacity-50 flex items-center justify-center z-50 w-screen overflow-y-auto">
    <div class="bg-gray-800 p-6 rounded shadow relative w-96">
      <button 
        @click="modalStore.close()" 
        class="absolute top-2 right-2 text-white hover:text-gray-100"
      >
        ✖
      </button>
      <h2 class="text-lg font-bold mb-4 text-gray-100">Add {{ modalStore.movieTitle }} to a List</h2>
      <div v-if="loading" class="text-gray-100">Loading your lists...</div>
      <div v-else>
        <label for="listSelect" class="block text-sm font-medium text-gray-100">Choose a list</label>
        <select 
          id="listSelect"
          v-model="selectedListId" 
          class="mt-3 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 py-2"
        >
          <option disabled value="" class="mt-2 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100">-- Select a list --</option>
          <option v-for="list in lists" :key="list.id" :value="list.id" class="mt-2 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100">
            {{ list.name }}
          </option>
        </select>

        <div class="mt-6 flex justify-end gap-2">
          <button @click="modalStore.close()" class="px-4 py-2 rounded bg-gray-300">Cancel</button>
          <button 
            @click="addToList" 
            :disabled="!selectedListId" 
            class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-500"
          >
            Add
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, inject } from 'vue'
import axiosClient from '../axios'
import { useAddMovieModalStore } from '../stores/modal.js'

const modalStore = useAddMovieModalStore()
const lists = ref([])
const loading = ref(false)
const selectedListId = ref("")
const triggerToast = inject('triggerToast');

// Watch the isOpen property of the store
watch(
  () => modalStore.isOpen,
  async (open) => {
    if (open && modalStore.movieId) {
      loading.value = true
      try {
        const { data } = await axiosClient.get('/api/movie-lists')
        lists.value = data
      } catch (err) {
        console.error("Could not fetch lists", err)
      } finally {
        loading.value = false
      }
    } else {
      lists.value = []
      selectedListId.value = ""
    }
  },
  { immediate: true }
)

// add movie to list
async function addToList() {
  if (!selectedListId.value) return
    await axiosClient.put(`/api/movie-lists/${selectedListId.value}`, { add_movie_id: modalStore.movieId })
      .then(() => {
        modalStore.close()
        triggerToast && triggerToast('Movie added to list!', 'success');
      })
      .catch(error => {
        console.log(error);
        triggerToast && triggerToast(error.response?.data?.message || 'Error adding movie!', 'error');
      });
}
</script>
<style scoped></style>