<script setup lang="ts">
import {useExportStore} from "@/stores/useExportStore.ts";
import {useOrdersStore} from "@/stores/useOrdersStore.ts";
import {onMounted, ref} from "vue";
import {useToast} from "@/stores/useToast.ts";

const exportStore = useExportStore();
const ordersStore = useOrdersStore();
const toast = useToast();

const dropDownVisible = ref(false);

const startExportOrders = async (format: ExportPayload) => {
  dropDownVisible.value = true;

  if (ordersStore.selectAllGlobal) {
    await exportStore.startExport({
      format,
      selectAll: true,
      selectedOrders: [],
      excludedOrders: ordersStore.excludedOrders,

      user_id: ordersStore.filters.user_id,
      status: ordersStore.filters.status,
      start_date: ordersStore.filters.start_date,
      end_date: ordersStore.filters.end_date,
      min_amount: ordersStore.filters.min_amount,
      max_amount: ordersStore.filters.max_amount,
    });
  } else {
    await exportStore.startExport({
      format,
      selectAll: false,
      selectedOrders: ordersStore.selectedOrders,
      excludedOrders: [],

      user_id: ordersStore.filters.user_id,
      status: ordersStore.filters.status,
      start_date: ordersStore.filters.start_date,
      end_date: ordersStore.filters.end_date,
      min_amount: ordersStore.filters.min_amount,
      max_amount: ordersStore.filters.max_amount,
    });
  }
};

const getDownloadUrl = (exportId: number) => `${import.meta.env.VITE_BACKEND_URL}/api/exports/download/${exportId}`;

onMounted(() => {
  exportStore.fetchExports();
});
</script>

<template>
  <div class="flex space-x-2">
    <button
      @click="startExportOrders('csv')"
      type="button"
      class="rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 rounded-lg dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800"
    >
      Export CSV
    </button>
    <button
      @click="startExportOrders('xlsx')"
      type="button"
      class="rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 rounded-lg dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800"
    >
      Export XLSX
    </button>
    <button
      @click="ordersStore.resetExportSelection"
      type="button"
      class="rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800"
    >
      Reset
    </button>
    <div v-if="exportStore.exportsList" class="relative">
      <button
        @click="dropDownVisible = !dropDownVisible"
        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
        type="button"
      >
        Exports List
        <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
        </svg>
      </button>
      <div
        v-show="dropDownVisible"
        class="absolute right-0 z-10 min-w-max max-h-[75vh] mt-2 overflow-y-auto bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700"
      >
        <ul
          v-if="exportStore.exportsList.length"
          class="py-2 text-sm text-gray-700 dark:text-gray-200"
          aria-labelledby="dropdownDefaultButton"
        >
          <li
            v-for="exportItem in exportStore.exportsList"
            :key="exportItem.id"
            class="relative"
          >
            <a
              v-if="exportItem.status === 'completed'"
              :href="getDownloadUrl(exportItem.id)"
              class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
            >
              <span class="font-medium">{{ exportItem.progressed }} #{{ exportItem.id }}</span>
              {{ exportItem.format.toUpperCase() }}
              {{ new Date(exportItem.created_at).toLocaleString() }}
              - Download
            </a>
            <div v-else class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
              <span class="font-medium">#{{ exportItem.id }}</span>
              ({{ exportItem.status }})
            </div>
            <div
              v-if="exportItem.status === 'in_progress'"
              class="absolute bottom-0 w-full bg-gray-200 rounded-full h-1.5 dark:bg-gray-700"
            >
              <div class="bg-blue-600 h-1.5 rounded-full dark:bg-blue-500" :style="{
                width: exportItem.total
                  ? ((exportItem.progressed / exportItem.total) * 100) + '%'
                  : '0%'
              }"></div>
            </div>
          </li>
        </ul>
        <span v-else class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 text-sm text-gray-700 dark:text-gray-200 dark:hover:text-white">
          No Exports List
        </span>
      </div>
    </div>
  </div>
</template>
