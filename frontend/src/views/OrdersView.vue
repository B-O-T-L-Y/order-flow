<script setup lang="ts">

import OrdersList from "@/components/orders/OrdersList.vue";
import OrdersAction from "@/components/orders/OrderAction.vue";
import OrdersFilterByDate from "@/components/orders/OrdersFilterByDate.vue";
import OrdersFilterByStatus from "@/components/orders/OrdersFilterByStatus.vue";
import OrdersFilterByAmount from "@/components/orders/OrdersFilterByAmount.vue";
import {useOrdersStore} from "@/stores/useOrdersStore.ts";

const orderStore = useOrdersStore();

const resetFilters = () => {
  orderStore.setDefaultFilters();
  orderStore.fetchOrders();
};
</script>

<template>
  <div class="p-5">
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
      <div class="flex items-center flex-column flex-wrap justify-between space-x-4 md:flex-row space-y-4 md:space-y-0 pb-4">
        <div class="flex items-center space-x-4">
          <OrdersAction/>
          <OrdersFilterByStatus/>
          <OrdersFilterByDate/>
          <OrdersFilterByAmount/>
          <button
            @click="resetFilters"
            class="px-5 py-2.5 focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900"
          >
            Reset Filters
          </button>
        </div>
      </div>
      <OrdersList/>
    </div>
  </div>
</template>
