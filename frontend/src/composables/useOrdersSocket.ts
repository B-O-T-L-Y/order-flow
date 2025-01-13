import {useWebSockets} from "@/composables/useWebSockets.ts";
import {useAuthStore} from "@/stores/useAuthStore.ts";
import {useOrdersStore} from "@/stores/useOrdersStore.ts";
import {useToast} from "@/stores/useToast.ts";
import {watch} from "vue";

export const useOrdersSocket = () => {
  const {echo} = useWebSockets();
  const auth = useAuthStore();
  const orderStore = useOrdersStore();
  const toast = useToast();

  watch(
    () => auth.user,
    user => {
      if (!user || !echo.value) return;

      console.log(`[WebSocket] Subscribing to orders.${user.id}`);

      const channel = echo.value.private(`orders.${user.id}`);

      channel.listen('.order.created', (event: any) => {
        console.log(`[WebSocket] New Order: ${event.order_id}`);
        // orderStore.addOrder();
        toast.showToast(`New Order Created. ID: ${event.order_id}`, 'success');
      });

      channel.listen('.order.updated', (event: any) => {
        console.log(`[WebSocket] Order Updated: ${event.order_id}`);
        // orderStore.updateOrder();
        toast.showToast(`Order Updated. ID: ${event.order_id}`, 'info');
      });

      channel.listen('.order.deleted', (event: any) => {
        console.log(`[WevScoket] Order Deleted: ${event.order_id}`);
        // orderStore.removeOrder();
        toast.showToast(`Order Deleted. ID: ${event.order_id}`, 'error');
      });
    },
    {immediate: true},
  );
};
