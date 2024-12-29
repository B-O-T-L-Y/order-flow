<script setup lang="ts">
import {useModalStore} from "@/stores/useModalStore.ts";

const modalStore = useModalStore();
</script>

<template>
  <div
    v-if="modalStore.visible"
    tabindex="-1"
    class="overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
  >
    <div class="relative top-1/2 left-1/2 -translate-y-1/2 -translate-x-1/2 p-4 w-full max-w-md max-h-full">
      <div class="relative bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
        <button
          @click.prevent="modalStore.closeModal()"
          class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
        >
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
          </svg>
          <span class="sr-only">Close modal</span>
        </button>
        <component :is="modalStore.component" v-bind="modalStore.componentProps"/>
      </div>
    </div>
  </div>
</template>
