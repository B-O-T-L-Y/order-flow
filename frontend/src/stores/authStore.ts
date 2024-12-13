import {defineStore} from "pinia";
import {ref, computed} from 'vue'
import router from "../router";
import {useApiFetch} from "@/composables/useApiFetch.ts";

export const authStore = defineStore('auth', () => {
  const user = ref<User | null>(null);
  const isLoggedIn = computed(() => !!user.value);

  const fetchUser = async (): Promise<void> => {
    const {data, error} = await useApiFetch('/api/user');

    user.value = data.value as User
    console.log(data, error)
  };

  const register = async (credentials: RegisterPayload): Promise<void> => {
    const {data, error} = await useApiFetch('/api/register', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify(credentials),
    });

    // await fetchUser();

    return {data, error}
  };

  const login = async (credentials: LoginPayload): Promise<void> => {
    await useApiFetch('/sanctum/csrf-cookie');

    const {data, error} = await useApiFetch('/api/login', {
      method: 'POST',
      body: credentials,
    });

    await fetchUser();

    return {data, error};
  };

  const logout = async (): Promise<void> => {
    await useApiFetch('/api/logout', {method: 'POST'});

    user.value = null;

    await router.push({path: '/login', replace: true});
  };

  return {
    user,
    isLoggedIn,
    login,
    register,
    logout,
  };
});
