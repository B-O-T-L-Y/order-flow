<script setup lang="ts">
import {onMounted, onUnmounted, ref, watch} from "vue";
import {useCartStore} from "@/stores/useCartStore.ts";
import {useProductsStore} from "@/stores/useProductsStore.ts";
import {useRoute, useRouter} from "vue-router"
import {useToast} from "@/stores/useToast.ts";

const route = useRoute();
const router = useRouter();
const productsStore = useProductsStore();
const cartStore = useCartStore();
const toast = useToast();

const fetchProducts = async (page: number = 1) => {
  if (page === 1 && route.params.page) {
    await router.push({path: `/`});
  } else if (page > 1) {
    await router.push({path: `/${page}`});
  }

  await productsStore.fetchProducts(page);
};

const addCart = (product: Product) => {
  cartStore.addToCart(product);

  toast.showToast(`Add <b>${product.name}</b> to cart.`, 'success', 2000);
};

watch(
  () => route.params.page,
  () => {
    const pageNum = parseInt(route.params.page as string) || 1;

    if (pageNum !== productsStore.pagination.currentPage) fetchProducts(pageNum);
  }
);

onMounted(() => fetchProducts(parseInt(route.params.page || 1)));

onUnmounted(() => fetchProducts());
</script>

<template>
  <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
    <div
      v-for="product in productsStore.products"
      :key="product.id"
      class="w-full max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700"
    >
      <img class="p-8 rounded-t-lg" src="https://flowbite.com/docs/images/products/apple-watch.png" alt="Product image"/>
      <div class="px-5 pb-5 space-y-4">
        <h5 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-white">{{ product.name }}</h5>
        <div class="flex items-center justify-between">
          <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ product.price }}</span>
          <button
            @click="addCart(product)"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
          >
            Add to Cart
          </button>
        </div>
      </div>
    </div>
  </div>
  <nav
    v-if="productsStore.pagination.lastPage > 1"
    aria-label="Page navigation example"
    class="mt-4"
  >
    <ul class="inline-flex -space-x-px text-sm">
      <li>
        <button
          @click="fetchProducts(productsStore.pagination.currentPage - 1)"
          :disabled="productsStore.pagination.currentPage === 1"
          aria-label="Previous"
          class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
        >
          Previous
        </button>
      </li>

      <li
        v-for="page in Array.from({ length: productsStore.pagination.lastPage }, (_, i) => i + 1)"
        :key="page"
      >
        <button
          @click="fetchProducts(page)"
          :aria-current="productsStore.pagination.currentPage === page ? 'page' : null"
          :aria-label="`Page ${page}`"
          :disabled="productsStore.pagination.currentPage === page"
          class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
          :class="{
          'bg-gray-100 text-gray-700 dark:bg-gray-500 dark:text-white': productsStore.pagination.currentPage === page,
        }"
        >
          {{ page }}
        </button>
      </li>

      <li>
        <button
          @click="fetchProducts(productsStore.pagination.currentPage + 1)"
          :disabled="productsStore.pagination.currentPage === productsStore.pagination.lastPage"
          aria-label="Next"
          class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
        >
          Next
        </button>
      </li>
    </ul>
  </nav>

</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
