import Echo from "laravel-echo";
import {ref, watch, watchEffect} from "vue";
import {useAuthStore} from "@/stores/useAuthStore.ts";
import {useApiFetch} from "@/composables/useApiFetch.ts";
import Pusher from "pusher-js";

const echo = ref<Echo | null>(null);
const isConnected = ref(false);

window.Pusher = Pusher;

export const useWebSockets = () => {
  const auth = useAuthStore();

  watchEffect(() => {
    if (!auth.user || echo.value) return;

    console.log('[WebSocket] Connecting...');

    echo.value = new Echo({
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

    isConnected.value = true;

    console.log('[WebSocket] Connected!');
  });

  watch(() => auth.user, user => {
    if (!user && echo.value) {
      console.log('[WebSocket] Disconnecting...');
      echo.value.disconnect();
      echo.value = null;
      isConnected.value = false;
    }
  });

  return {echo, isConnected};
};
