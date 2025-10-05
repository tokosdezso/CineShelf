
<script setup>
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/20/solid'
import { defineEmits, computed } from 'vue';

const emit = defineEmits(['paginate']);

const props = defineProps({
  pagination: {
    type: Object,
    required: true,
  }
});

// Define the number of visible page buttons
const maxVisible = 5

// Emit the search event
function goToPage(page) {
  if (page < 1 || page > props.pagination.total_pages) return
  props.pagination.page = page;
  emit('paginate', page);
}

const pages = computed(() => {
  const half = Math.floor(maxVisible / 2)
  let start = Math.max(2, props.pagination.page - half)
  let end = Math.min(props.pagination.total_pages - 1, props.pagination.page + half)

  // ha túl közel van az elejéhez
  if (props.pagination.page <= half) {
    start = 2
    end = Math.min(props.pagination.total_pages - 1, maxVisible)
  }

  // ha túl közel van a végéhez
  if (props.pagination.page >= props.pagination.total_pages - half) {
    start = Math.max(2, props.pagination.total_pages - maxVisible + 1)
    end = props.pagination.total_pages - 1
  }

  return Array.from({ length: end - start + 1 }, (_, i) => start + i)
});

</script>

  <template>
  <div v-if="props.pagination.total_pages > 1" class="flex items-center justify-between border-t border-white/10 px-4 py-3 sm:px-6">
    <div class="flex flex-1 justify-between sm:hidden">
      <button
        @click="goToPage(props.pagination.page - 1)"
        :disabled="props.pagination.page === 1"
        class="relative inline-flex items-center rounded-md border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium text-gray-200 hover:bg-white/10"
      >
        <span>Previous</span>
      </button>
      <button
        @click="goToPage(props.pagination.page + 1)"
        :disabled="props.pagination.page === props.pagination.total_pages"
        class="relative ml-3 inline-flex items-center rounded-md border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium text-gray-200 hover:bg-white/10"
      >
        <span class="">Next</span>
      </button>
    </div>
    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
      <div>
        <p class="text-sm text-gray-200">
          Showing
          {{ ' ' }}
          <span class="font-medium">{{ (pagination.page - 1) * (pagination.per_page ?? 20) + 1  }}</span>
          {{ ' ' }}
          to
          {{ ' ' }}
          <span class="font-medium">{{ pagination.page * (pagination.per_page ?? 20) }}</span>
          {{ ' ' }}
          of
          {{ ' ' }}
          <span class="font-medium">{{ pagination.total_results }}</span>
          {{ ' ' }}
          results
        </p>
      </div>
      <div>
        <nav class="isolate inline-flex -space-x-px rounded-md" aria-label="Pagination">
          <button
            @click="goToPage(props.pagination.page - 1)"
            :disabled="props.pagination.page === 1"
            class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 inset-ring inset-ring-gray-700 hover:bg-white/5 disabled:opacity-40"
          >
            <span class="sr-only">Previous</span>
            <ChevronLeftIcon class="size-5" aria-hidden="true" />
          </button>
          <button
            @click="goToPage(1)"
            :class="[
              'relative inline-flex items-center px-4 py-2 text-sm font-semibold focus:z-20',
              props.pagination.page === 1
                ? 'z-10 bg-indigo-500 text-white'
                : 'text-gray-200 inset-ring inset-ring-gray-700 hover:bg-white/5'
            ]"
          >
            1
          </button>

          <!-- Left dots -->
          <span v-if="pages[0] > 2" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-400">
            ...
          </span>

          <!-- Middle dynamic pages -->
          <button
            v-for="page in pages"
            :key="page"
            @click="goToPage(page)"
            :class="[
              'relative inline-flex items-center px-4 py-2 text-sm font-semibold focus:z-20',
              props.pagination.page === page
                ? 'z-10 bg-indigo-500 text-white'
                : 'text-gray-200 inset-ring inset-ring-gray-700 hover:bg-white/5'
            ]"
          >
            {{ page }}
          </button>

          <!-- Right dots -->
          <span v-if="pages[pages.length - 1] < props.pagination.total_pages - 1" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-400">
            ...
          </span>

          <button
            @click="goToPage(props.pagination.total_pages)"
            :class="[
              'relative inline-flex items-center px-4 py-2 text-sm font-semibold focus:z-20',
              props.pagination.page === props.pagination.total_pages
                ? 'z-10 bg-indigo-500 text-white'
                : 'text-gray-200 inset-ring inset-ring-gray-700 hover:bg-white/5'
            ]"
          >
            {{ props.pagination.total_pages }}
          </button>
          <button
            @click="goToPage(props.pagination.page + 1)"
            :disabled="props.pagination.page === props.pagination.total_pages"
            class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 inset-ring inset-ring-gray-700 hover:bg-white/5 disabled:opacity-40"
          >
            <span class="sr-only">Next</span>
            <ChevronRightIcon class="size-5" aria-hidden="true" />
          </button>
        </nav>
      </div>
    </div>
  </div>
</template>