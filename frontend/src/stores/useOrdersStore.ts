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

  const predefineStatuses = [
    {label: 'All', value: ''},
    {label: 'New', value: 'new'},
    {label: 'Processing', value: 'processing'},
    {label: 'Shipped', value: 'shipped'},
    {label: 'Delivered', value: 'delivered'},
  ];

  const predefinedDateRanges = [
    {label: 'Last Day', start: new Date(Date.now() - 24 * 60 * 60 * 100), end: new Date()},
    {label: 'Last 7 Days', start: new Date(Date.now() - 7 * 24 * 60 * 60 * 100), end: new Date()},
    {label: 'Last 30 Days', start: new Date(Date.now() - 30 * 24 * 60 * 60 * 100), end: new Date()},
    {label: 'Last Month', start: new Date(new Date().setMonth(new Date().getMonth() - 1)), end: new Date()},
    {label: 'Last Year', start: new Date(new Date().setFullYear(new Date().getFullYear() - 1)), end: new Date()},
  ];

  const setDefaultFilters = () => {
    Object.assign(filters, {
      status: '',
      start_date: predefinedDateRanges[2].start.toISOString().split('T')[0],
      end_date: predefinedDateRanges[2].end.toISOString().split('T')[0],
      min_amount: null,
      max_amount: null,
    });
  };

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

  return {
    orders,
    pagination,
    filters,
    predefineStatuses,
    predefinedDateRanges,
    fetchOrders,
    setDefaultFilters,
  };
});
