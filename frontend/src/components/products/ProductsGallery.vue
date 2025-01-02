<script setup lang="ts">
import {onMounted, ref} from "vue";
import {useCartStore} from "@/stores/useCartStore.ts";
import {useApiFetch} from "@/composables/useApiFetch.ts";

const products = ref([]);
const cartStore = useCartStore();
const cartProductNmae = ref<string | null>(null);
const showSuccess = ref<boolean>(false);

const fetchProducts = async () => {
  const {data, error} = await useApiFetch('/api/products').json();

  if (!error.value) {
    products.value = data.value.data;
  }
};

const addCart = (product: Product) => {
  cartStore.addToCart(product);

  cartProductNmae.value = product.name;
  showSuccess.value = true;

  setTimeout(() => {
    cartProductNmae.value = null;
    showSuccess.value = false;
  }, 3000);
};

onMounted(fetchProducts);
</script>

<template>
  <transition name="fade">
    <div
      v-if="showSuccess"
      class="fixed top-4 right-4 bg-green-100 text-green-800 px-4 py-2 rounded-lg shadow-lg">
      Add <b>{{ cartProductNmae }}</b> to cart.
    </div>
  </transition>

  <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
    <div
      v-for="product in products"
      :key="product.id"
      class="w-full max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700"
    >
      <img class="p-8 rounded-t-lg" src="https://flowbite.com/docs/images/products/apple-watch.png" alt="Product image"/>
      <div class="px-5 pb-5">
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
