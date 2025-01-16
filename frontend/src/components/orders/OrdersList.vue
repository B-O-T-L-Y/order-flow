<script setup lang="ts">
import {markRaw, onMounted, onUnmounted, ref, watch} from "vue";
import {useOrdersStore} from "@/stores/useOrdersStore.ts";
import {useModalStore} from "@/stores/useModalStore.ts";
import OrdersDeleteConfirm from "@/components/orders/OrdersDeleteConfirm.vue";
import {useAuthStore} from "@/stores/useAuthStore.ts";
import OrderEditForm from "@/components/forms/OrderEditForm.vue";
import {useRoute, useRouter} from "vue-router";

const route = useRoute();
const router = useRouter();
const auth = useAuthStore();
const ordersStore = useOrdersStore();
const modalStore = useModalStore();
const expendedOrderId = ref<number | null>(null);

const openEditForm = (order) => {
  modalStore.openModal({
    component: markRaw(OrderEditForm),
    props: {
      order,
      onSave: async updateData => {
        const {error} = await ordersStore.updateOrder(order.id, updateData);

        if (!error.value) {
          modalStore.closeModal();
        }

        return {error};
      },
      onCancel: () => modalStore.closeModal(),
    }
  });
};

const toggleExpendedOrder = (orderId: number) => {
  expendedOrderId.value = expendedOrderId.value === orderId ? null : orderId;
};

const openDeleteConfirmation = (orderId: number) => {
  modalStore.openModal({
    component: markRaw(OrdersDeleteConfirm),
    props: {
      onConfirm: () => {
        deleteOrder(orderId);
        modalStore.closeModal();
      },
      onCancel: () => modalStore.closeModal(),
    }
  });
};

const deleteOrder = async (orderId: number) => {
  const {error} = await ordersStore.deleteOrder(orderId);

  if (error.value) {
    console.log('Failed to delete order', error.value);
  }

  modalStore.closeModal();
};

const orders = ref([]);

const fetchOrders = async (page: number = 1) => {
  if (page === 1 && route.params.page) {
    await router.push({path: '/orders'});
  } else if (page > 1) {
    await router.push({path: `/orders/${page}`});
  }

  await ordersStore.fetchOrders(page);
};

const changePage = (page: number) => {
  if (page > 0 && page <= ordersStore.pagination.lastPage) {
    fetchOrders(page);
  }
};

onMounted(() => {
  ordersStore.setDefaultFilters();
  fetchOrders(parseInt(route.params.page || 1));
});

onUnmounted(() => {
  if (auth.user) {
    fetchOrders();
  }
});

watch(
  () => route.params.page,
  () => {
    const pageNum = parseInt(route.params.page as string) || 1;

    if (pageNum !== ordersStore.pagination.currentPage) fetchOrders(pageNum);
  }
);
</script>

<template>
  <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
    <tr>
      <th scope="col" class="p-4">
        <div class="flex items-center">
          <input
            @change="ordersStore.toggleAllSelected"
            :checked="ordersStore.allSelected"
            id="checkbox-all"
            type="checkbox"
            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-900 dark:border-gray-600"
          >
          <label for="checkbox-all" class="sr-only">checkbox</label>
        </div>
      </th>
      <th scope="col" class="px-2.5 py-3">#</th>
      <th scope="col" class="px-2.5 py-3">Status</th>
      <th v-if="auth.user?.is_admin" scope="col" class="px-2.5 py-3">Customer ID</th>
      <th v-if="auth.user?.is_admin" scope="col" class="px-2.5 py-3">Customer</th>
      <th v-if="auth.user?.is_admin" scope="col" class="px-2.5 py-3">Email</th>
      <th scope="col" class="px-2.5 py-3">Products</th>
      <th scope="col" class="px-2.5 py-3">Amount</th>
      <th scope="col" class="px-2.5 py-3">Created At</th>
      <th scope="col" class="px-2.5 py-3">Updated At</th>
      <th v-if="auth.user?.is_admin" scope="col" colspan="2" class="text-center px-2.5 py-3">Actions</th>
    </tr>
    </thead>
    <tbody>
    <tr
      v-for="(order, index) in ordersStore.orders"
      :key="order.id"
      :class="{'border-b': index !== orders.length - 1}"
      class="bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
    >
      <td class="w-4 p-4">
        <div class="flex items-center">
          <input
            v-model="ordersStore.selectedOrders"
            :value="order.id"
            :id="`checkbox-table-${index}`"
            type="checkbox"
            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
          >
          <label :for="`checkbox-table-${index}`" class="sr-only">checkbox</label>
        </div>
      </td>
      <td class="px-2.5 py-4">{{ order.id }}</td>
      <td class="px-2.5 py-4">
        <span
          class="px-3 py-1 rounded-full text-xs font-semibold"
          :class="{
            'bg-gray-200 text-gray-700': order.status === 'new',
            'bg-yellow-200 text-yellow-700': order.status === 'processing',
            'bg-blue-200 text-blue-700': order.status === 'shipped',
            'bg-green-200 text-green-700': order.status === 'delivered',
          }"
        >
        {{ ordersStore.predefineStatuses.find(item => item.value === order.status)?.label }}
        </span>
      </td>
      <td v-if="auth.user?.is_admin" class="px-2.5 py-4">{{ order.user.id }}</td>
      <td v-if="auth.user?.is_admin" class="px-2.5 py-4 font-medium text-gray-900 dark:text-white">{{ order.user.name }}</td>
      <td v-if="auth.user?.is_admin" class="px-2.5 py-4">{{ order.user.email }}</td>
      <td class="px-2.5 py-4">
        <ul class="space-y-2">
          <li
            v-for="product in (expendedOrderId === order.id ? order.products : order.products.slice(0, 2))"
            :key="product.id"
            class="flex items-center space-x-2"
          >
            <img src="https://flowbite.com/docs/images/products/imac.png" alt="Product" class="w-8 h-8 rounded-full">
            <span>{{ product.name }}</span>
            <span>x{{ product.pivot.quantity }}</span>
            <span class="text-gray-500">{{ product.pivot.price }}</span>
          </li>
        </ul>
        <button
          v-if="order.products.length > 2"
          @click="toggleExpendedOrder(order.id)"
          class="mt-2 font-bold text-blue-500 hover:underline"
        >
          {{ expendedOrderId === order.id ? 'Hide Details' : `Show All products (${order.products.length})` }}
        </button>
      </td>
      <td class="px-2.5 py-4">{{ order.amount }}</td>
      <td class="px-2.5 py-4">{{ new Date(order.created_at).toUTCString() }}</td>
      <td class="px-2.5 py-4">{{ new Date(order.updated_at).toUTCString() }}</td>
      <td v-if="auth.user?.is_admin" class="px-2.5 py-4">
        <button
          @click="openEditForm(order)"
          class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
        >
          Edit Order
        </button>
      </td>
      <td v-if="auth.user?.is_admin" class="px-2.5 py-4">
        <button
          @click="openDeleteConfirmation(order.id)"
          class="font-medium text-red-600 dark:text-red-500 hover:underline"
        >
          Remove
        </button>
      </td>
    </tr>
    </tbody>
  </table>
  <nav
    v-if="ordersStore.pagination.lastPage > 1"
    aria-label="Page navigation example"
    class="mt-4"
  >
    <ul class="inline-flex -space-x-px text-sm">
      <li>
        <button
          @click="changePage(ordersStore.pagination.currentPage - 1)"
          :disabled="ordersStore.pagination.currentPage === 1"
          aria-label="Previous"
          class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
        >
          Previous
        </button>
      </li>
      <li
        v-for="page in Array.from({length: ordersStore.pagination.lastPage}, (_, i) => i + 1)"
        :key="page"
      >
        <button
          @click="changePage(page)"
          :aria-current="ordersStore.pagination.currentPage === page ? 'page' : null"
          :aria-label="`Page ${page}`"
          :disabled="ordersStore.pagination.currentPage === page"
          class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
          :class="{'bg-gray-100 text-gray-700 dark:bg-gray-500 dark:text-white': ordersStore.pagination.currentPage === page}"
        >
          {{ page }}
        </button>
      </li>
      <li>
        <button
          @click="changePage(ordersStore.pagination.currentPage + 1)"
          :disabled="ordersStore.pagination.currentPage === ordersStore.pagination.lastPage"
          aria-label="Next"
          class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
        >
          Next
        </button>
      </li>
    </ul>
  </nav>
</template>
