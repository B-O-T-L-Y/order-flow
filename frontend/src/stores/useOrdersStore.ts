import {useApiFetch} from "@/composables/useApiFetch.ts";
import {defineStore} from "pinia";

export const useOrdersStore = defineStore('orders', () => {
  const fetchOrders = async (filters: Record<string, any>): Promise<void> => {
    const query = new URLSearchParams(filters).toString();
    const endpoint = query ? `/api/orders?${query}` : '/api/orders';

    const {data, error} = await useApiFetch(endpoint).json();

    return {data, error};
  }

  return {
    fetchOrders,
  };
});
