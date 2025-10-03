

<script setup>
import router from '../router.js';
import { BackwardIcon } from '@heroicons/vue/24/solid';

const props = defineProps({
  textColor: {
    type: String,
    required: true
  },
  backgroundColor: {
    type: String,
    required: true
  },
});

const goBack = () => {
  router.go(-1);
}
</script>

<template>
  <button 
    @click="goBack"
    :class="textColor+ ' ' + backgroundColor + ' flex rounded-full px-6 py-3 text-m shadow-sm '"
  >
    <BackwardIcon class="w-6 h-6 mr-2" />
    Back
  </button>
</template>

<style scoped>
</style>