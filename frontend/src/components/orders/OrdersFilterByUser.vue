<script setup lang="ts">
import {useOrdersStore} from "@/stores/useOrdersStore.ts";
import {computed, ref, watch} from "vue";
import router from "@/router";

const ordersStore = useOrdersStore();
let debounceTimeout: ReturnType<typeof setTimeout> | null = null;

const userId = computed({
  get: () => ordersStore.filters.user_id,
  set: (value: number | null) => {
    ordersStore.filters.user_id = value !== null && value > 0 ? value : null;
  },
});

watch(userId, newValue => {
  if (debounceTimeout) {
    clearTimeout(debounceTimeout);
  }

  debounceTimeout = setTimeout(() => {
    ordersStore.filters.user_id = newValue;
    router.push({path: '/orders'});
    ordersStore.fetchOrders();
  }, 500);
  },
);
</script>

<template>
  <div>
    <label for="amount-max" class="sr-only">Filter by User ID</label>
    <div class="relative">
      <div class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
        <svg class="w-3.5 h-3.5 text-gray-500 dark:text-gray-400 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
          <path fill-rule="evenodd" d="M12 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4h-4Z" clip-rule="evenodd"/>
        </svg>

      </div>
      <input
        v-model="userId"
        type="number"
        id="user-id"
        min="0"
        class="[appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none block p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-60 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
        placeholder="Enter user ID..."
      >
    </div>
  </div>
</template>
