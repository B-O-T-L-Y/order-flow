<script setup lang="ts">
import DefaultHeader from "../components/DefaultHeader.vue";
import DefaultFooter from "../components/DefaultFooter.vue";
</script>

<template>
  <DefaultHeader/>
  <main class="pt-6 bg-white dark:bg-gray-800 antialiased h-screen">
    <RouterView/>
  </main>
  <DefaultFooter/>
</template>

