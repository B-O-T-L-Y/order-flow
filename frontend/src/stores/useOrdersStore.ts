import {useApiFetch} from "@/composables/useApiFetch.ts";
import {defineStore} from "pinia";

export const useOrdersStore = defineStore('orders', () => {
  const fetchOrders = async (page: number = 1, filters: Record<string, any>): Promise<void> => {
    const query = new URLSearchParams({...filters, page: String(page)}).toString();
    const endpoint = `/api/orders?${query}`;

    const {data, error} = await useApiFetch(endpoint).json();

    return {data: data.value, error};
  }

  return {
    fetchOrders,
  };
});
