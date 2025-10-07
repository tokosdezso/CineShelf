<template>
  <div class="min-h-full">
    <Disclosure as="nav" class="bg-gray-800/50" v-slot="{ open }">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
          <div class="flex items-center">
            <div class="shrink-0">
              <img class="h-9 w-auto" src="/movie.svg" alt="CineShelf" />
            </div>
            <div class="hidden md:block">
              <div class="ml-10 flex items-baseline space-x-4">
                <RouterLink v-for="item in navigation"
                  :key="item.name"
                  :to="item.to"
                  :class="[$route.name === item.to.name ? 'bg-gray-950/50 text-white' : 'here text-gray-300 hover:bg-white/5 hover:text-white', 'rounded-md px-3 py-2 text-sm font-medium']"
                  :aria-current="$route.name === item.name ? 'page' : undefined"
                >
                  {{ item.name }}
                </RouterLink>
              </div>
            </div>
          </div>
          <div class="hidden md:block">
            <div class="ml-4 flex items-center md:ml-6">
              <!-- Profile dropdown -->
              <Menu as="div" class="relative ml-3">
                <MenuButton class="relative flex max-w-xs items-center rounded-full focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">
                  <span class="absolute -inset-1.5" />
                  <span class="sr-only">Open user menu</span>
                  <UserIcon class="size-8 p-1 rounded-full outline -outline-offset-1 outline-white/10" />
                  <div class="text-base/5 text-gray-300 text-white p-2">{{ user.name }}</div>                
                </MenuButton>

                <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform scale-100" leave-to-class="transform opacity-0 scale-95">
                  <MenuItems class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-gray-800 py-1 outline-1 -outline-offset-1 outline-white/10">
                    <MenuItem>
                      <button @click="logout" :class="['block px-4 py-2 text-sm text-gray-300']">
                        Sign out
                      </button>
                    </MenuItem>
                  </MenuItems>
                </transition>
              </Menu>
            </div>
          </div>
          <div class="-mr-2 flex md:hidden">
            <!-- Mobile menu button -->
            <DisclosureButton class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-white/5 hover:text-white focus:outline-2 focus:outline-offset-2 focus:outline-indigo-500">
              <span class="absolute -inset-0.5" />
              <span class="sr-only">Open main menu</span>
              <Bars3Icon v-if="!open" class="block size-6" aria-hidden="true" />
              <XMarkIcon v-else class="block size-6" aria-hidden="true" />
            </DisclosureButton>
          </div>
        </div>
      </div>

      <DisclosurePanel class="md:hidden">
        <div class="space-y-1 px-2 pt-2 pb-3 sm:px-3">
          <RouterLink v-for="item in navigation" 
            :key="item.name"
            :to="item.to"
            :class="[$route.name === item.to.name ? 'bg-gray-950/50 text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white', 'block rounded-md px-3 py-2 text-base font-medium']"
            :aria-current="item.current ? 'page' : undefined"
          >
            {{ item.name }}
          </RouterLink>
        </div>
        <div class="border-t border-white/10 pt-4 pb-3">
          <div class="flex items-center px-5">
            <div class="shrink-0">
              <UserIcon class="size-10 p-1 rounded-full outline -outline-offset-1 outline-white/10" />
            </div>
            <div class="ml-3">
              <div class="text-base/5 font-medium text-white">{{ user.name }}</div>
              <div class="text-sm font-medium text-gray-400">{{ user.email }}</div>
            </div>
          </div>
          <div class="mt-3 space-y-1 px-2">
            <DisclosureButton @click="logout" 
              class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-white/5 hover:text-white"
            >
              Sign out
            </DisclosureButton>
          </div>
        </div>
      </DisclosurePanel>
    </Disclosure>
    <RouterView />

    <AddToMovieListModal v-if="modalStore.isOpen" />
    <Toast :message="toastMessage" :show="showToast" :type="toastType" />
  </div>
</template>

<script setup>
import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { Bars3Icon, XMarkIcon, UserIcon } from '@heroicons/vue/24/outline'
import { RouterLink } from 'vue-router'
import axiosClient from '../../axios'
import router from '../../router'
import { computed } from 'vue'
import useUserStore from '../../stores/user.js'
import { useAddMovieModalStore } from '../../stores/modal.js'
import AddToMovieListModal from './../AddToMovieListModal.vue'
import { ref, provide } from 'vue';
import Toast from '../Toast.vue';

const userStore = useUserStore();

const user = computed(() => userStore.user);

const navigation = [
  { name: 'Movies', to: {name: 'Home'}, current: true },
  { name: 'My Lists', to: {name: 'MyLists'}, current: false },
]

const toastType = ref('success');
const toastMessage = ref('');
const showToast = ref(false);

const modalStore = useAddMovieModalStore()

function logout() {
  axiosClient.post('/logout')
    .then(() => {
      router.push({ name: 'Login' })
  });
}

// show toast
function triggerToast(message, type = 'success') {
  toastMessage.value = message;
  toastType.value = type;
  showToast.value = true;
  setTimeout(() => {
    showToast.value = false;
    toastMessage.value = '';
  }, 2500);
}

provide('triggerToast', triggerToast);
</script>