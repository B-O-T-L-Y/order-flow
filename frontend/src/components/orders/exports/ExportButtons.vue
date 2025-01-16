<script setup lang="ts">
import {useExportStore} from "@/stores/useExportStore.ts";
import {useOrdersStore} from "@/stores/useOrdersStore.ts";
import {onMounted} from "vue";

const exportStore = useExportStore();
const ordersStore = useOrdersStore();

const startExportOrders = async (format: ExportOrders) => {
  if (ordersStore.selectedOrders.length > 0) {
    await exportStore.startExport(format, ordersStore.selectedOrders);
    await exportStore.fetchExports();
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
  </div>
  <div v-if="exportStore.exports.value" class="text-white">
    <h3>Available Exports</h3>
    <ul>
      <li v-for="exportItem in exportStore.exports.value" :key="exportItem.id">
        <a
          :href="getDownloadUrl(exportItem.id)"
          class=""
          target="_blank"
        >
          Download {{ exportItem.format.toUpperCase() }} ({{ new Date(exportItem.created_at).toLocaleString() }})
        </a>
      </li>
    </ul>
  </div>
</template>
