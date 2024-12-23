<script setup lang="ts">

import OrdersList from "@/components/orders/OrdersList.vue";
import OrdersAction from "@/components/orders/OrderAction.vue";
import OrdersSearch from "@/components/orders/OrdersSearch.vue";
import OrdersFilterByDate from "@/components/orders/OrdersFilterByDate.vue";
import OrdersFilterByStatus from "@/components/orders/OrdersFilterByStatus.vue";
</script>

<template>
  <div class="p-5">
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
      <div class="flex items-center flex-column flex-wrap justify-between space-x-4 md:flex-row space-y-4 md:space-y-0 pb-4">
        <div class="flex items-center space-x-4">
          <OrdersAction/>
          <OrdersFilterByStatus/>
          <OrdersFilterByDate/>
        </div>
        <OrdersSearch/>
      </div>
      <OrdersList/>
    </div>
  </div>
</template>
