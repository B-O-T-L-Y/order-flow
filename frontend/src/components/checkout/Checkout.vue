<script setup lang="ts">
import {useCartStore} from "@/stores/useCartStore.ts";
import {useApiFetch} from "@/composables/useApiFetch.ts";
import router from "@/router";

const cartStore = useCartStore();

const checkout = async () => {
  const {data, error, statusCode} = await useApiFetch('/api/checkout', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({items: cartStore.cart}),
  }).json();

  if (statusCode.value === 200) {
    cartStore.clearCart();
    await router.push({path: '/orders', replace: true});
  } else {
    console.log('Failed to checkout', error.value);
  }
};
</script>

<template>

</template>
