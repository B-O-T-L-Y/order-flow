import {useApiFetch} from "@/composables/useApiFetch.ts";
import {defineStore} from "pinia";
import {useAuthStore} from "@/stores/useAuthStore.ts";

export const useOrdersStore = defineStore('orders', () => {
  const auth = useAuthStore();

  const fetchOrders = async (page: number = 1, filters: Record<string, any>): Promise<void> => {
    const query = new URLSearchParams({...filters, page: String(page)}).toString();
    const endpoint = `/api/orders?${query}`;

    const {data, error, statusCode} = await useApiFetch(endpoint).json();

    if (statusCode.value === 401) {
      await auth.logout();
    }

    return {data: data.value, error};
  }

  return {
    fetchOrders,
  };
});
