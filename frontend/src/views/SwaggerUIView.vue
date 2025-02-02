<script setup lang="ts">
import SwaggerUI from "swagger-ui";
import "swagger-ui/dist/swagger-ui.css";
import {onMounted, ref} from "vue";
import {useCookies} from "@vueuse/integrations/useCookies";

const apiDocsUrl = `${import.meta.env.VITE_BACKEND_URL}/api/docs`;
const swaggerContainer = ref<HTMLElement | null>(null);
const cookies = useCookies();
const csrfToken = cookies.get("XSRF-TOKEN");

onMounted(() => {
  if (swaggerContainer.value) {
    SwaggerUI({
      domNode: swaggerContainer.value,
      url: apiDocsUrl,
      deepLinking: true,
      persistAuthorization: true,
      requestInterceptor: (req) => {
        req.credentials = "include";
        req.headers["X-XSRF-TOKEN"] = csrfToken;
        return req;
      },
    });
  }
});
</script>

<template>
  <div class="swagger-container">
    <div ref="swaggerContainer"></div>
<!--    <div id="#swagger-ui"></div>-->
  </div>
</template>

<style>
.swagger-container {
  width: 100%;
  height: 100vh;
  background-color: white;
}
</style>
