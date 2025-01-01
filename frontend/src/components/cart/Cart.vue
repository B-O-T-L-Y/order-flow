<script setup lang="ts">
import CartItemList from "@/components/cart/CartItemList.vue";

defineProps<{
  onCancel: Function;
}>();
</script>

<template>
  <div class="mx-auto px-8 py-4 space-y-6">
    <h5 class="text-xl font-medium text-gray-900 dark:text-white">Edit Cart</h5>
    <CartItemList :isCheckout="true" :onCancel="onCancel"/>
  </div>
</template>
