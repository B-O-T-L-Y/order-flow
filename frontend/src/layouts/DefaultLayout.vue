<script setup lang="ts">
import DefaultHeader from "../components/DefaultHeader.vue";
import DefaultFooter from "../components/DefaultFooter.vue";
</script>

<template>
  <div>
    <DefaultHeader />
    <main>
      <RouterView />
    </main>
    <DefaultFooter />
  </div>
</template>
