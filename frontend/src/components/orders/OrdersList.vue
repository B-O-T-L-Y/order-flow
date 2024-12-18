<script setup lang="ts">
import {onMounted, ref} from "vue";
import {useOrdersStore} from "@/stores/useOrdersStore.ts";

const orders = useOrdersStore();
const filters = ref({
  status: '',
  start_date: '',
  end_date: '',
});

const fetchOrders = async () => {
  const errors = ref<Record<string, string[]>>({});
  const {data, error} = await orders.fetchOrders(filters.value);

  if (error) {
    // errors.value = error.body.error.details;

    return;
  }

  console.log(data.value.body.orders)

  orders.value = data.value.orders; // TODO update backend. Change data to orders
};

onMounted(fetchOrders);
</script>

<template>
  <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
    <tr>
      <th scope="col" class="p-4">
        <div class="flex items-center">
          <input id="checkbox-all-search" type="checkbox"
                 class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
          <label for="checkbox-all-search" class="sr-only">checkbox</label>
        </div>
      </th>
      <th scope="col" class="px-6 py-3">#</th>
      <th scope="col" class="px-6 py-3">Customer</th>
      <th scope="col" class="px-6 py-3">Status</th>
      <th scope="col" class="px-6 py-3">Products</th>
      <th scope="col" class="px-6 py-3">Update At</th>
    </tr>
    </thead>
    <tbody>
    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
      <td class="w-4 p-4">
        <div class="flex items-center">
          <input id="checkbox-table-search-1" type="checkbox"
                 class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
          <label for="checkbox-table-search-1" class="sr-only">checkbox</label>
        </div>
      </td>
      <td class="px-6 py-4">React Developer</td>
      <td class="px-6 py-4">
        <div class="flex items-center">
          <div class="h-2.5 w-2.5 rounded-full bg-green-500 me-2"></div>
          Online
        </div>
      </td>
      <td class="px-6 py-4">
        <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit user</a>
      </td>
    </tr>
    </tbody>
  </table>
</template>
