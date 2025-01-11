import Pusher from "pusher-js";
import Echo from "laravel-echo";
import {useApiFetch} from "@/composables/useApiFetch.ts";
import {useAuthStore} from "@/stores/useAuthStore.ts";
import {useToast} from "@/stores/useToast.ts";

window.Pusher = Pusher;

window.Echo = new Echo({
  broadcaster: 'reverb',
  key: import.meta.env.VITE_REVERB_APP_KEY,
  wsHost: import.meta.env.VITE_REVERB_HOST,
  wsPort: import.meta.env.VITE_REVERB_PORT,
  wssPort: import.meta.env.VITE_REVERB_PORT,
  forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? "https") === "https",
  enabledTransports: ["ws", "wss"],
  authorizer: (channel, options) => {
    return {
      authorize: async (socketId, callback) => {
        const {data, error} = await useApiFetch(import.meta.env.VITE_REVERB_AUTH_ENDPOINT, {
          headers: {'Content-Type': 'application/json'},
          method: "POST",
          body: JSON.stringify({
            socket_id: socketId,
            channel_name: channel.name,
          }),

        }).json();

        if (data.value) callback(false, data.value);
        if (error.value) callback(true, error.value);
      },
    };
  },
});

let orderChannel: any = null;

export const subscribeToOrders = () => {
  const auth = useAuthStore();
  const toast = useToast();

  if (!auth.user) return;

  const userId = auth.user?.id;
  const isAdmin = auth.user?.is_admin;

  if (isAdmin) {
    return;
  }

  if (orderChannel) {
    orderChannel.stopListening('.order.created');
    window.Echo.leave(`orders.${userId}`);
  }

  window.Echo.leave(`orders.${userId}`);

  orderChannel = window.Echo.private(`orders.${userId}`)
    .listen('.order.created', (event: any) => {
      console.log(`New order created. ID: ${event.order_id}`);
      // toast.showToast(`New order created. ID: ${event.order_id}`, 'message');
    });

  return;
};
