import {defineStore} from "pinia";
import {ref, computed} from 'vue'
import router from "../router";
import {useApiFetch} from "@/composables/useApiFetch.ts";

export const useAuthStore = defineStore('auth', () => {
  const user = ref<User | null>(null);
  const isLoggedIn = computed(() => !!user.value);
  let isSessionVerified = false;

  async function csrfCookie() {
    await useApiFetch('/sanctum/csrf-cookie');
  }

  const fetchUser = async (): Promise<void> => {
    // await csrfCookie();

    const {data, error} = await useApiFetch('/api/user', {
      method: 'GET',
      headers: {'Content-Type': 'application/json'},
    }).json();

    if (isSessionVerified) return;

    user.value = data.value as User
    console.log(data, error)
  };

  const register = async (credentials: RegisterPayload): Promise<void> => {
    const {error, statusCode} = await useApiFetch('/api/register', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify(credentials),
    }).json();

    if (statusCode.value === 201) {
      await router.push({path: '/login', replace: true});
    }

    return {error};
  };

  const login = async (credentials: LoginPayload): Promise<void> => {
    await csrfCookie();

    const {data, error} = await useApiFetch('/api/login', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify(credentials),
    }).json();

    user.value = data.value.user as User

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
