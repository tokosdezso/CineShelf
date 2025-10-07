<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
  message: String,
  show: Boolean,
  type: {
    type: String,
    default: 'success'
  },
  duration: {
    type: Number,
    default: 2500
  }
});

const visible = ref(false);

// Watch for changes in the show prop to control visibility
watch(() => props.show, (val) => {
  if (val) {
    visible.value = true;
    setTimeout(() => {
      visible.value = false;
    }, props.duration);
  }
});
</script>

<template>
  <div v-if="visible" class="fixed top-4 right-4 z-50 px-4 py-2 rounded shadow transition-opacity duration-300 text-gray-100"
    :class="[
      { 'opacity-0': !visible, 'opacity-100': visible },
      type === 'error' ? 'bg-red-600' : 'bg-green-600'
    ]">
    {{ message }}
  </div>
</template>

