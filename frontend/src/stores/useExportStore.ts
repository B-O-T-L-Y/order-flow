import {computed, onMounted, ref, watchEffect} from "vue";
import {useApiFetch} from "@/composables/useApiFetch.ts";
import {useToast} from "@/stores/useToast.ts";
import {useAuthStore} from "@/stores/useAuthStore.ts";
import {useWebSockets} from "@/composables/useWebSockets.ts";
import {defineStore} from "pinia";

export const useExportStore = defineStore('exportStore', () => {
  const auth = useAuthStore();
  const toast = useToast();
  const exportsMap = ref<Record<number, ExportItem>>({});

  const fetchExports = async (): Promise<void> => {
    const {data} = await useApiFetch('/api/exports/').json();
    const items = data.value.data as ExportItem[];

    const newMap: Record<number, ExportItem> = {};
    for (const exp of items) {
      newMap[exp.id] = {
        ...exp,
        progress: exp.progress ?? 0,
        total: exp.total ?? 0,
      };
    }
    exportsMap.value = newMap;
  };

  const startExport = async (format: ExportOrders, selectedOrders: number[]): Promise<void> => {
    await useApiFetch('/api/exports', {
      headers: {'Content-Type': 'application/json'},
      method: 'POST',
      body: JSON.stringify({format, selected_orders: selectedOrders}),
    }).json();
  };

  const exportsList = computed<ExportItem[]>(() => Object.values(exportsMap.value).sort((a, b) => {
    return b.created_at.localeCompare(a.created_at);
  }));

  const updateExport = (exp: ExportItem) => exportsMap.value[exp.id] = exp;

  return {
    exportsMap,
    exportsList,
    fetchExports,
    startExport,
    updateExport,
  };
});
