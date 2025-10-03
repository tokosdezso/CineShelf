
<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

const query = ref('')
const emit = defineEmits(['search'])

// Emit the search event
function emitSearch() {
  if (query.value.length < 3) return;
  emit('search', query.value)
}

// Enter keydown
function handleKeydown(e) {
  if (e.key === 'Enter') {
    emitSearch();
  }
}

onMounted(() => {
  window.addEventListener('keydown', handleKeydown);
});

onUnmounted(() => {
  window.removeEventListener('keydown', handleKeydown);
});

</script>

<template>
  <div class="search-bar pb-6">
    <input
      type="text"
      v-model="query"
      placeholder="Search..."
      class="border p-2 rounded-lg bg-gray-100 mb-2 mr-2 w-full sm:w-96 flex-grow text-gray-900"
    />
    <button 
      @click="emitSearch"
      class="px-4 py-2 bg-indigo-600 text-gray-100 rounded hover:bg-indigo-500"
    >
      Search
    </button>
  </div>
</template>

