import {createFetch} from "@vueuse/core";
import {useCookies} from "@vueuse/integrations/useCookies";

export const useApiFetch = createFetch({
  baseUrl: import.meta.env.VITE_BACKEND_URL as string,
  options: {
    async beforeFetch({options}):Promise<any> {
      const cookies = useCookies();
      const token = cookies.get('XSRF-TOKEN');

      if (token) {
        options.headers = {
          ...options?.headers,
          'X-XSRF-TOKEN': token,
        };
      }

      return {options};
    },
  },
  fetchOptions: {
    mode: 'cors',
  }
});
