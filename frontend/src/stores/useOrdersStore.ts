import {useApiFetch} from "@/composables/useApiFetch.ts";
import {defineStore} from "pinia";
import {useAuthStore} from "@/stores/useAuthStore.ts";
import {ref} from "vue";
import router from "@/router";

export const useOrdersStore = defineStore('orders', () => {
  const auth = useAuthStore();
  const orders = ref([]);
  const pagination = ref({
    currentPage: 1,
    lastPage: 1,
    nextPageUrl: '',
    prevPageUrl: '',
  });
  const filters = ref({
    status: '',
    start_date: '',
    end_date: '',
    // min_total: null,
    // max_total: null,
  });

  const fetchOrders = async (page: number = 1): Promise<void> => {
    const query = new URLSearchParams({...filters.value, page: String(page)}).toString();
    const endpoint = `/api/orders?${query}`;

    const {data, error, statusCode} = await useApiFetch(endpoint).json();

    if (statusCode.value === 200) {
      orders.value = data.value.data;
      pagination.value = {
        currentPage: data.value.current_page,
        lastPage: data.value.last_page,
        nextPageUrl: data.value.next_page_url,
        prevPageUrl: data.value.prev_page_url,
      };
    }

    if (statusCode.value === 401) {
      auth.user.value = null;

      await router.push({path: '/login', replace: true});
    }

    return {data: data.value, error};
  }

  const setFilters = (newFilters: Record<string, any>) => {
    filters.value = {...filters.value, ...newFilters};
  };

  const resetFilters = () => {
    filters.value = {
      status: '',
      start_date: '',
      end_date: '',
      min_total: null,
      max_total: null,
    };
  };

  return {
    orders,
    pagination,
    filters,
    fetchOrders,
    setFilters,
    resetFilters,
  };
});
