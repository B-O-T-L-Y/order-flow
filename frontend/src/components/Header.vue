<script setup lang="ts">
import {useAuthStore} from "@/stores/useAuthStore.ts";
import {useModalStore} from "@/stores/useModalStore.ts";
import Cart from "@/components/cart/Cart.vue";

const auth = useAuthStore();
const modalStore = useModalStore();

const openModalStore = () => {
  modalStore.openModal({
    component: Cart,
    props: {
      onCancel: () => modalStore.closeModal(),
    }
  });
};
</script>

<template>
  <header>
    <nav class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-600">
      <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
        <a href="https://flowbite.com/" class="flex items-center space-x-3 rtl:space-x-reverse">
          <img src="https://flowbite.com/docs/images/logo.svg" class="h-8" alt="Flowbite Logo"/>
          <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Order Tracking System</span>
        </a>
        <div class="flex w-auto order-1 items-center justify-between space-x-8">
          <ul
            class="flex flex-col font-medium p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
            <li>
              <RouterLink
                to="/" class="block py-2 px-3 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 md:dark:text-blue-500"
                aria-current="page"
              >
                Products
              </RouterLink>
            </li>
            <li v-if="auth.user">
              <RouterLink
                to="/orders" class="block py-2 px-3 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 md:dark:text-blue-500"
                aria-current="page"
              >
                Orders
              </RouterLink>
            </li>
            <li v-if="!auth.user">
              <RouterLink
                to="/register" class="block py-2 px-3 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 md:dark:text-blue-500"
                aria-current="page"
              >
                Register
              </RouterLink>
            </li>
            <li v-if="!auth.user">
              <RouterLink
                to="/login"
                class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700"
              >
                Login
              </RouterLink>
            </li>
            <li v-if="auth.user">
              <a
                href="#" @click.prevent="auth.logout()"
                class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700"
              >
                Logout
              </a>
            </li>
          </ul>
          <button
            @click="openModalStore"
            type="button"
            class="rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center me-2 focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300  rounded-lg dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800"
          >
            <svg class="w-3.5 h-3.5 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 21">
              <path
                d="M15 12a1 1 0 0 0 .962-.726l2-7A1 1 0 0 0 17 3H3.77L3.175.745A1 1 0 0 0 2.208 0H1a1 1 0 0 0 0 2h.438l.6 2.255v.019l2 7 .746 2.986A3 3 0 1 0 9 17a2.966 2.966 0 0 0-.184-1h2.368c-.118.32-.18.659-.184 1a3 3 0 1 0 3-3H6.78l-.5-2H15Z"/>
            </svg>
            Buy now
          </button>
        </div>
      </div>
    </nav>
  </header>
</template>
