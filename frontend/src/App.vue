<script setup lang="ts">
import { RouterView } from 'vue-router'
import Toast from "@/components/Toast.vue";
import {onMounted, watchEffect} from "vue";
import {useAuthStore} from "@/stores/useAuthStore.ts";
import {subscribeToOrders} from "@/websocket.ts";

const auth = useAuthStore();

watchEffect(() => {
  if (auth.user) {
    subscribeToOrders()
  }
});
</script>

<template>
  <Toast/>
  <RouterView />
</template>
