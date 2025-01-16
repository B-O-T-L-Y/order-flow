import {ref, watchEffect} from "vue";
import {useApiFetch} from "@/composables/useApiFetch.ts";
import {useToast} from "@/stores/useToast.ts";
import {useAuthStore} from "@/stores/useAuthStore.ts";
import {useWebSockets} from "@/composables/useWebSockets.ts";

export const useExportStore = () => {
  const auth = useAuthStore();
  const {echo} = useWebSockets();
  const toast = useToast();
  const exports = ref();

  const fetchExports = async (): Promise<void> => {
    const {data} = await useApiFetch('/api/exports/').json();

    exports.value = data.value.data;
  };

  const startExport = async (format: ExportOrders, selectedOrders: number[]): Promise<void> => {
    await useApiFetch('/api/exports', {
      headers: {'Content-Type': 'application/json'},
      method: 'POST',
      body: JSON.stringify({format, selected_orders: selectedOrders}),
    }).json();
  };

  watchEffect(() => {
    if (!auth?.user?.is_admin) return;


  });

  return {
    exports,
    fetchExports,
    startExport,
  };
};
