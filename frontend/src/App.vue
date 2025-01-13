<script setup lang="ts">
import { RouterView } from 'vue-router'
import ToastNotification from "@/components/ToastNotification.vue";
import {useOrdersSocket} from "@/composables/useOrdersSocket.ts";
import {useAdminOrdersSocket} from "@/composables/useAdminOrdersSocket.ts";

useAdminOrdersSocket();
useOrdersSocket();
</script>

<template>
  <ToastNotification/>
  <RouterView />
</template>
