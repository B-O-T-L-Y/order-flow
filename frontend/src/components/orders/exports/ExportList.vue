<script setup lang="ts">
import {useExportStore} from "@/stores/useExportStore.ts";
import {onMounted} from "vue";

const {exports, fetchExports} = useExportStore();

onMounted(fetchExports);
</script>
<template>
  <div>
    <h2 class="">Exports list</h2>
    <table class="">
      <thead>
      <tr>
        <th class="">Date</th>
        <th class="">Format</th>
        <th class="">File</th>
      </tr>
      </thead>
      <tbody class="">
      <tr v-for="exportItem in exports" :key="exportItem.id">
        <td class="">{{ new Date(exportItem.created_at).toLocaleString() }}</td>
        <td class="">{{ exportItem.format }}</td>
        <td>
          <a :href="`/storage${exportItem.file_path}`" target="_blank" class="Download"></a>
        </td>
      </tr>
      </tbody>
    </table>
  </div>
</template>
