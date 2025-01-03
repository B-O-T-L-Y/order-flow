import {defineStore} from "pinia";
import {useApiFetch} from "@/composables/useApiFetch.ts";
import {ref} from "vue";

export const useProductsStore = defineStore('products', () => {
  const products = ref([]);
  const pagination = ref({
    currentPage: 1,
    lastPage: 1,
  });

  const fetchProducts = async (page: number = 1): Promise<void> => {
    const endpoint = `/api/products?page=${page}`;
    const {data, statusCode} = await useApiFetch(endpoint).json();

    if (statusCode.value === 200) {
      products.value = data.value.data;
      pagination.value = {
        currentPage: data.value.current_page,
        lastPage: data.value.last_page,
      };
    }
  };

  return {
    products,
    pagination,
    fetchProducts,
  };
});
