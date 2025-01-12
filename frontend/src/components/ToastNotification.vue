<script setup lang="ts">
import {useToast} from "@/stores/useToast.ts";
import Toast from "@/components/Toast.vue";

const {ToastNotification} = useToast();
</script>

<template>
  <ToastNotification v-slot="slotProps">
    <Toast :slotProps="slotProps" />
  </ToastNotification>
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
