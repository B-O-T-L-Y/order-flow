<script setup lang="ts">
import {useOrdersStore} from "@/stores/useOrdersStore.ts";
import {computed, ref} from "vue";

const orderStore = useOrdersStore();

const minAmount = computed({
  get: () => orderStore.filters.min_amount,
  set: (value: number | null) => {
    orderStore.filters.min_amount = value !== null && value >= 0 ? value : null;
  },
});

const maxAmount = computed({
  get: () => orderStore.filters.max_amount,
  set: (value: number | null) => {
    orderStore.filters.max_amount = value !== null && value >= 0 ? value : null;
  },
});
</script>

<template>
  <div class="flex items-center">
    <div>
      <label for="amount-max" class="sr-only">Search</label>
      <div class="relative">
        <div class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
          <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
               viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19V5m0 14-4-4m4 4 4-4"/>
          </svg>
        </div>
        <input
          v-model="minAmount"
          type="number"
          id="amount-max"
          min="0"
          class="[appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none block p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
          placeholder="Min. Amount"
        >
      </div>
    </div>
    <span class="mx-4 text-gray-500">to</span>
    <div>
      <label for="amount-min" class="sr-only">Search</label>
      <div class="relative">
        <div class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
          <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
               viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v13m0-13 4 4m-4-4-4 4"/>
          </svg>
        </div>
        <input
          v-model="maxAmount"
          type="number"
          id="amount-min"
          min="0"
          class="[appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none block p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
          placeholder="Max. Amount"
        >
      </div>
    </div>
    <button
      @click="orderStore.fetchOrders()"
      class="px-5 py-2.5 text-sm font-medium text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
    >
      Apply
    </button>
  </div>
</template>
