import {createFetch} from "@vueuse/core";
import {useCookies} from "@vueuse/integrations/useCookies";

export const useApiFetch = createFetch({
  baseUrl: import.meta.env.VITE_BACKEND_URL as string,
  options: {
    async beforeFetch({options}) {
      const cookies = useCookies();
      const token = cookies.get("XSRF-TOKEN");

      options.headers = {
        ...options?.headers,
        ...(token ? {"X-XSRF-TOKEN": token} : {}),
        Accept: "application/json",
      };

      return {options};
    },
    async afterFetch(ctx) {
      return ctx;
    },
    async onFetchError(ctx) {
      const {response} = ctx;

      if (response) {
        const errorBody = await response.json().catch(() => ({}));
        ctx.error = {
          status: response.status,
          statusText: response.statusText,
          body: errorBody,
        };
      } else {
        ctx.error = {
          message: "Unknown error occurred",
          status: null,
        };
      }

      ctx.data = null;

      return ctx;
    },
  },
  fetchOptions: {
    mode: "cors",
    credentials: "include",
  },
});
