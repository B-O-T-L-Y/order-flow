import {useApiFetch} from "@/composables/useApiFetch.ts";
import {defineStore} from "pinia";
import {useAuthStore} from "@/stores/useAuthStore.ts";
import {reactive, ref} from "vue";
import router from "@/router";

export const useOrdersStore = defineStore('orders', () => {
  const auth = useAuthStore();
  const orders = ref([]);
  const pagination = ref({
    currentPage: 1,
    lastPage: 1,
  });
  const filters = reactive({
    status: '',
    start_date: '',
    end_date: '',
    min_amount: null,
    max_amount: null,
  });

  const fetchOrders = async (page: number = 1): Promise<void> => {
    const cleanFilters = Object.fromEntries(Object.entries({...filters, page: String(page)}).filter(([_, value]) => value));
    const query = new URLSearchParams(cleanFilters).toString();
    const endpoint = `/api/orders?${query}`;

    const {data, error, statusCode} = await useApiFetch(endpoint).json();

    if (statusCode.value === 200) {
      orders.value = data.value.data;
      pagination.value = {
        currentPage: data.value.current_page,
        lastPage: data.value.last_page,
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
    Object.assign(filters, {
      status: '',
      start_date: new Date(Date.now() - 30 * 24 * 60 * 60 * 100).toISOString().split('T')[0],
      end_date: new Date().toISOString().split('T')[0],
      min_amount: null,
      max_amount: null,
    });
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
