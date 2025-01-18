<script setup lang="ts">
import { RouterView } from 'vue-router'
import ToastNotification from "@/components/ToastNotification.vue";
import {useOrdersSocket} from "@/composables/useOrdersSocket.ts";
import {useExportSocket} from "@/composables/useExportSocket.ts";

useOrdersSocket();
useExportSocket();
</script>

<template>
  <ToastNotification/>
  <RouterView />
</template>
