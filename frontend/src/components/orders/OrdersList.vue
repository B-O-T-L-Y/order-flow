<script setup lang="ts">
import {onMounted, onUnmounted, ref} from "vue";
import {useOrdersStore} from "@/stores/useOrdersStore.ts";

const ordersStore = useOrdersStore();
const orders = ref([]);

const fetchOrders = async (page = 1) => {
  // const errors = ref<Record<string, string[]>>({});
  const {error} = await ordersStore.fetchOrders(page);

  // if (data) {
  //   orders.value = data.data;
  // }
};

const changePage = (page: number) => {
  if (page > 0 && page <= ordersStore.pagination.lastPage) {
    fetchOrders(page);
  }
};

onMounted(fetchOrders);
onUnmounted(() => ordersStore.setDefaultFilters());
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
      <th scope="col" class="px-6 py-3">Status</th>
      <th scope="col" class="px-6 py-3">Customer</th>
      <th scope="col" class="px-6 py-3">Email</th>
      <th scope="col" class="px-6 py-3">Products</th>
      <th scope="col" class="px-6 py-3">Amount</th>
      <th scope="col" class="px-6 py-3">Created At</th>
      <th scope="col" class="px-6 py-3">Updated At</th>
      <th scope="col" class="px-6 py-3">Action</th>
    </tr>
    </thead>
    <tbody>
    <tr
      v-for="(order, index) in ordersStore.orders"
      :key="order.id"
      :class="{'border-b': index !== orders.length - 1}"
      class="bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
    >
      <td class="w-4 p-4">
        <div class="flex items-center">
          <input id="checkbox-table-search-1" type="checkbox"
                 class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
          <label for="checkbox-table-search-1" class="sr-only">checkbox</label>
        </div>
      </td>
      <td class="px-6 py-4">{{ order.id }}</td>
      <td class="px-6 py-4">{{ order.status }}</td>
      <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ order.user.name }}</td>
      <td class="px-6 py-4">{{ order.user.email }}</td>
      <td class="px-6 py-4">
        <ul>
          <li v-for="product in order.products" :key="product.id" class="flex flex-wrap">
            {{ product.name }} - {{ product.price }}
          </li>
        </ul>
      </td>
      <td class="px-6 py-4">{{ order.amount }}</td>
      <td class="px-6 py-4">{{ new Date(order.created_at).toUTCString() }}</td>
      <td class="px-6 py-4">{{ new Date(order.updated_at).toUTCString() }}</td>
      <td class="px-6 py-4">
        <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit Order</a>
      </td>
    </tr>
    </tbody>
  </table>
  <nav
    v-if="ordersStore.pagination.lastPage > 1"
    aria-label="Page navigation example"
    class="mt-4"
  >
    <ul class="inline-flex -space-x-px text-sm">
      <li>
        <button
          @click="changePage(ordersStore.pagination.currentPage - 1)"
          :disabled="ordersStore.pagination.currentPage === 1"
          aria-label="Previous"
          class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
        >
          Previous
        </button>
      </li>
      <li
        v-for="page in Array.from({length: ordersStore.pagination.lastPage}, (_, i) => i + 1)"
        :key="page"
      >
        <button
          @click="changePage(page)"
          :aria-current="ordersStore.pagination.currentPage === page ? 'page' : null"
          :aria-label="`Page ${page}`"
          :disabled="ordersStore.pagination.currentPage === page"
          class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
          :class="{'bg-gray-100 text-gray-700 dark:bg-gray-500 dark:text-white': ordersStore.pagination.currentPage === page}"
        >
          {{ page }}
        </button>
      </li>
      <li>
        <button
          @click="changePage(ordersStore.pagination.currentPage + 1)"
          :disabled="ordersStore.pagination.currentPage === ordersStore.pagination.lastPage"
          aria-label="Next"
          class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
        >
          Next
        </button>
      </li>
    </ul>
  </nav>
</template>
