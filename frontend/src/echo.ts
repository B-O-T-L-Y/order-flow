import Pusher from "pusher-js";
import Echo from "laravel-echo";
import {useFetch} from "@vueuse/core";
import {useCookies} from "@vueuse/integrations/useCookies";
import {useApiFetch} from "@/composables/useApiFetch.ts";

window.Pusher = Pusher;

window.Echo = new Echo({
  broadcaster: 'reverb',
  // authEndpoint: 'http://localhost:8000/broadcasting/auth',
  key: import.meta.env.VITE_REVERB_APP_KEY,
  wsHost: import.meta.env.VITE_REVERB_HOST,
  wsPort: import.meta.env.VITE_REVERB_PORT,
  wssPort: import.meta.env.VITE_REVERB_PORT,
  forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? "https") === "https",
  enabledTransports: ["ws", "wss"],
  // auth: {
  //   headers: {
  //     ...(token ? {'X-XSRF-TOKEN': useCookies().get('XSRF-TOKEN')} : {}),
  //     // Authorization: useCookies('accessToken').value,  // If using token-based auth
  //   },
  // },
  // 👇 Кастомный авторизатор через useFetch
  authorizer: (channel, options) => {
    return {
      authorize: async (socketId, callback) => {
        const {data, error} = await useApiFetch('/broadcasting/auth', {
          headers: {'Content-Type': 'application/json'},
          method: "POST",
          body: JSON.stringify({
            socket_id: socketId,
            channel_name: channel.name,
          }),

        }).json();

        console.log(data);

        if (data.value) {
          console.log("Authorization success", data.value);
          await callback(false, data.value);
        }

        if (error.value) {
          console.error("Authorization failed", error.value);
          callback(true, error.value);
        }
      },
    };
  },
});
