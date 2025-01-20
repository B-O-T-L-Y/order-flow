<script setup lang="ts">
import {useToast} from "@/stores/useToast.ts";
import Toast from "@/components/Toast.vue";

const {ToastNotification} = useToast();
</script>

<template>
  <div class="pointer-events-none fixed z-20 flex flex-col-reverse space-y-4 space-y-reverse items-center w-full max-w-xs text-gray-500 divide-x rtl:divide-x-reverse divide-gray-200 rounded-lg shadow top-16 right-4 dark:text-gray-400 dark:divide-gray-700" role="alert">
      <ToastNotification v-slot="slotProps" :key="Date.now()">
        <Toast :slotProps="slotProps"/>
      </ToastNotification>
  </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active,
.fade-move {
  transition: transform 0.5s ease, opacity 0.5s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
