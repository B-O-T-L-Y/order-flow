<script setup lang="ts">
import {reactive, ref} from "vue";
import {useAuthStore} from "@/stores/useAuthStore.ts";
import {useRoute} from "vue-router";

const route = useRoute();

const form = reactive<LoginPayload>({
  email: '',
  password: '',
});
const auth = useAuthStore();
const errors = ref<Record<string, string[]>>({});

const login = async (): Promise<void> => {
  errors.value = {};

  const redirectPath = route.query.redirect as string;

  const {error} = await auth.login(form, redirectPath);

  if (error.value) {
    errors.value = error.value.body.error.details;
  }
};
</script>

<template>
  <form @submit.prevent="login" class="space-y-6">
    <h5 class="text-xl font-medium text-gray-900 dark:text-white">Sign in</h5>
    <div>
      <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your email</label>
      <input
        v-model="form.email"
        type="email"
        name="email"
        id="email"
        placeholder="email@example.com"
        autocomplete="username"
        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
      />
      <p v-if="errors?.email" class="mt-2 text-sm text-red-600 dark:text-red-500">{{ errors.email[0] }}</p>
    </div>
    <div>
      <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your password</label>
      <input
        v-model="form.password"
        type="password"
        name="password"
        id="password"
        placeholder="••••••••"
        autocomplete="current-password"
        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
      />
      <p v-if="errors?.password" class="mt-2 text-sm text-red-600 dark:text-red-500">{{ errors.password[0] }}</p>
    </div>
    <div class="flex items-start">
      <div class="flex items-start">
        <div class="flex items-center h-5">
          <input
            id="remember"
            type="checkbox"
            value=""
            class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800"
          />
        </div>
        <label for="remember" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Remember me</label>
      </div>
      <!--        <a href="#" class="ms-auto text-sm text-blue-700 hover:underline dark:text-blue-500">Lost Password?</a>-->
    </div>
    <button
      type="submit"
      class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
    >
      Login
    </button>
    <div class="text-sm font-medium text-gray-500 dark:text-gray-300">
      Not registered?
      <RouterLink to="/register" class="text-blue-700 hover:underline dark:text-blue-500">Create account</RouterLink>
    </div>
  </form>
</template>
