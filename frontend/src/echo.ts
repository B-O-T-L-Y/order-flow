import Pusher from "pusher-js";
import Echo from "laravel-echo";
import {useApiFetch} from "@/composables/useApiFetch.ts";

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
