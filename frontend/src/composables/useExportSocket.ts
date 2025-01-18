import {useWebSockets} from "@/composables/useWebSockets.ts";
import {useAuthStore} from "@/stores/useAuthStore.ts";
import {useExportStore} from "@/stores/useExportStore.ts";
import {watch} from "vue";
import {useToast} from "@/stores/useToast.ts";

export const useExportSocket = () => {
  const {echo} = useWebSockets();
  const auth = useAuthStore();
  const exportStore = useExportStore();
  const toast = useToast();

  let channel = null;

  watch(
    () => auth.user,
    user => {
      if (!user || !echo.value) return;

      if (user.is_admin) {
        console.log(`[WebSocket] Subscribing to admin.exports`);

        channel = echo.value.private('admin.exports');

        channel.listen('.export.progress', (payload: any) => {
          const exp: ExportItem = payload.export
          const progress: number = payload.progress;
          const total: number = payload.total;

          const newExp: ExportItem = {
            ...exp,
            progress,
            total,
          };

          exportStore.updateExport(newExp);

          console.log('[WebSocket] Export Progress: ',  exp.id, progress, total);
        });

        channel.listen('.export.completed', (payload: any) => {
          const exp: ExportItem = payload.export;

          exportStore.updateExport(exp);

          console.log('[WebSocket] Export Completed: ', exp.id);
          toast.showToast(`Export #${exp.id} completed!`, 'success');
        });

        channel.listen('.export.failed', (payload: any) => {
          const exp: ExportItem = payload.export;

          exportStore.updateExport(exp);

          console.log('[WebSocket] Export Failed: ', exp.id);
          toast.showToast(`Export #${exp.id} failed!`, 'error');
        });
      }
    },
    {immediate: true},
  );
};
