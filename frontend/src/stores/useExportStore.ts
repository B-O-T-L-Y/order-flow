import {computed, onMounted, ref, watchEffect} from "vue";
import {useApiFetch} from "@/composables/useApiFetch.ts";
import {useToast} from "@/stores/useToast.ts";
import {useAuthStore} from "@/stores/useAuthStore.ts";
import {useWebSockets} from "@/composables/useWebSockets.ts";
import {defineStore} from "pinia";
import {data} from "autoprefixer";

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

  const startExport = async (payload: ExportPayload): Promise<void> => {
    const {data, error, statusCode} = await useApiFetch('/api/exports', {
      headers: {'Content-Type': 'application/json'},
      method: 'POST',
      body: JSON.stringify({
        format: payload.format,
        select_all: payload.selectAll,
        selected_orders: payload.selectedOrders,
        excluded_orders: payload.excludedOrders,

        user_id: payload.user_id,
        status: payload.status,
        start_date: payload.start_date,
        end_date: payload.end_date,
        min_amount: payload.min_amount,
        max_amount: payload.max_amount,
      }),
    }).json();

    if (statusCode.value === 422 && error.value) {
      await toast.showToast(error.value.body.error.details.data_selected[0], 'error');

      return;
    }

    if (statusCode.value === 200 && data?.value?.message) {
      await toast.showToast(data.value.message, 'success');

      return;
    }

    await toast.showToast('Failed to start export.', 'error');
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
