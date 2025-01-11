import {useApiFetch} from "@/composables/useApiFetch.ts";
import {defineStore} from "pinia";
import {useAuthStore} from "@/stores/useAuthStore.ts";
import {reactive, ref} from "vue";
import {useRouter} from "vue-router";

export const useOrdersStore = defineStore('orders', () => {
  const router = useRouter();
  const auth = useAuthStore();
  const orders = ref([]);

  const pagination = ref({
    currentPage: 1,
    lastPage: 1,
  });

  const filters = reactive({
    user_id: '',
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
    {label: 'Last Day', start: new Date(Date.now() - 24 * 60 * 60 * 1000), end: new Date()},
    {label: 'Last 7 Days', start: new Date(Date.now() - 7 * 24 * 60 * 60 * 1000), end: new Date()},
    {label: 'Last 30 Days', start: new Date(Date.now() - 30 * 24 * 60 * 60 * 1000), end: new Date()},
    {label: 'Last Month', start: new Date(new Date().setMonth(new Date().getMonth() - 1)), end: new Date()},
    {label: 'Last Year', start: new Date(new Date().setFullYear(new Date().getFullYear() - 1)), end: new Date()},
  ];

  const defaultRange = predefinedDateRanges[2];
  const selectedRange = ref(defaultRange);

  const setDefaultFilters = async () => {
    selectedRange.value = defaultRange;

    Object.assign(filters, {
      user_id: '',
      status: '',
      start_date: defaultRange.start.toISOString().split('T')[0],
      end_date: defaultRange.end.toISOString().split('T')[0],
      min_amount: null,
      max_amount: null,
    });

    await router.push({path: '/orders'});
  };

  const updateOrder = async (orderId: number, data: { status: string }): Promise<void> => {
    const {error, statusCode} = await useApiFetch(`/api/orders/${orderId}`, {
      method: 'PATCH',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify(data),
    }).json();

    if (statusCode.value === 200) {
      const index = orders.value.findIndex(order => order.id === orderId);

      if (index !== -1) {
        orders.value[index] = {...orders.value[index], ...data};
      }
    }

    return {error};
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
  };

  const deleteOrder = async (orderId: number): Promise<void> => {
    const {error, statusCode} = await useApiFetch(`/api/orders/${orderId}`, {method: 'DELETE'}).json();

    if (statusCode.value === 200) {
      orders.value = orders.value.filter(order => order.id !== orderId);
    } else {
      console.log('Failed to delete order', error.value);
    }

    return {error};
  };

  return {
    orders,
    pagination,
    filters,
    predefineStatuses,
    predefinedDateRanges,
    selectedRange,
    updateOrder,
    fetchOrders,
    setDefaultFilters,
    deleteOrder,
  };
});
