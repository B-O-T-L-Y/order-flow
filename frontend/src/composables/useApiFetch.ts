import {createFetch} from "@vueuse/core";
import {useCookies} from "@vueuse/integrations/useCookies";

export const useApiFetch = createFetch({
  baseUrl: import.meta.env.VITE_BACKEND_URL as string,
  options: {
    async beforeFetch({options}):Promise<any> {
      const cookies = useCookies();
      const token = cookies.get('XSRF-TOKEN');

      options.headers = {
        ...options?.headers,
        ...(token ? { 'X-XSRF-TOKEN': token } : {}),
        Accept: 'application/json',
      };

      return {options};
    },
  },
  fetchOptions: {
    mode: 'cors',
    credentials: 'include',
  }
});
