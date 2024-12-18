import {defineStore} from "pinia";
import router from "../router";
import {useApiFetch} from "@/composables/useApiFetch.ts";
import useLocalstorage from "@/composables/useLocalStorage.ts";

export const useAuthStore = defineStore('auth', () => {
  const user = useLocalstorage<User>('auth.user');
  let isSessionVerified = false;

  const fetchUser = async (): Promise<void> => {
    isSessionVerified = true;
    const {data, error} = await useApiFetch('/api/user').json();

    user.value = data.value.user as User
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

  const login = async (credentials: LoginPayload): Promise<any> => {
    const {error, statusCode} = await useApiFetch('/api/login', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify(credentials),
    }).json();

    if (statusCode.value === 200) {
      await fetchUser();

      await router.push({path: '/orders', replace: true});
    }

    return {error};
  };

  const logout = async (): Promise<void> => {
    await useApiFetch('/api/logout', {method: 'POST'});

    user.value = null;

    await router.push({path: '/login', replace: true});
  };

  const verifySession = async (): Promise<void> => {
    if (user.value && !isSessionVerified) {
      try {
        await fetchUser();
      } catch (err) {
        user.value = null;
      }
    }
  }

  return {
    verifySession,
    user,
    // isLoggedIn,
    login,
    register,
    logout,
  };
});
