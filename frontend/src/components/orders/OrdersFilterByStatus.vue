<script setup lang="ts">
import {useOrdersStore} from "@/stores/useOrdersStore.ts";
import {computed, onMounted, onUnmounted, ref} from "vue";

const orderStore = useOrdersStore();

const selectedStatus = computed({
  get: () => orderStore.filters.status,
  set: (value: string) => {
    orderStore.filters.status = value;
    orderStore.fetchOrders();
  },
});

const dropDownVisible = ref(false);

const closeDropDown = (event: MouseEvent) => {
  const target = event.target as HTMLElement;

  if (!target.closest('.status-dropdown')) {
    dropDownVisible.value = false;
  }
};

onMounted(() => {
  document.addEventListener('click', closeDropDown);
});

onUnmounted(() => {
  document.removeEventListener('click', closeDropDown);
});
</script>

<template>
  <div class="relative status-dropdown">
    <button
      @click.prevent="dropDownVisible = !dropDownVisible"
      class="inline-flex items-center text-gray-500 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
    >
      <svg class="w-3.5 h-3.5 text-gray-500 dark:text-gray-400 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
           fill="currentColor" viewBox="0 0 24 24">
        <path fill-rule="evenodd"
              d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm13.707-1.293a1 1 0 0 0-1.414-1.414L11 12.586l-1.793-1.793a1 1 0 0 0-1.414 1.414l2.5 2.5a1 1 0 0 0 1.414 0l4-4Z"
              clip-rule="evenodd"/>
      </svg>
      {{ orderStore.predefineStatuses.find(item => item.value === selectedStatus)?.label }}
      <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
      </svg>
    </button>
    <div
      v-show="dropDownVisible"
      class="absolute top-full translate-y-2 block z-100 w-48 bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600"
    >
      <ul class="p-3 space-y-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownRadioButton">
        <li
          v-for="(status, index) in orderStore.predefineStatuses"
          :key="status.label"
        >
          <div
            @click.prevent="selectedStatus = status.value"
            class="flex items-center p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-600"
            :class="{'pointer-events-none bg-gray-100 dark:bg-gray-600': selectedStatus === status.value}"
          >
            <input
              type="radio"
              :id="`filter-radio-${index}`"
              :checked="selectedStatus === status.value"
              class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
            >
            <label
              :for="`filter-radio-${index}`"
              class="w-full ms-2 text-sm font-medium text-gray-900 rounded dark:text-gray-300"
            >
              {{ status.label }}
            </label>
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>
