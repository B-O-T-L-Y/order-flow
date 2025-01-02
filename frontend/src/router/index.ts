import {createRouter, createWebHistory, type RouteLocation} from 'vue-router'
import RegisterView from "../views/RegisterView.vue";
import OrdersView from "../views/OrdersView.vue";
import NotFoundView from "@/views/NotFoundView.vue";
import DefaultLayout from "@/layouts/DefaultLayout.vue";
import AuthLayout from "@/layouts/AuthLayout.vue";
import LoginView from "@/views/LoginView.vue";
import {useAuthStore} from "@/stores/useAuthStore.ts";
import ProductsView from "@/views/ProductsView.vue";
import CheckoutView from "@/views/CheckoutView.vue";

async function auth(to: RouteLocation, from: RouteLocation) {
  const auth = useAuthStore();

  if (!auth.user) {
    return {
      path: '/login',
      query: {redirect: to.fullPath},
    };
  }
}

async function guest(to: RouteLocation, from: RouteLocation) {
  const auth = useAuthStore();

  await auth.verifySession();

  if (auth.user) {
    return '/orders';
  }
}

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      component: DefaultLayout,
      children: [
        {
          path: '',
          name: 'Products',
          component: ProductsView,
        },
      ],
    },
    {
      path: '/',
      component: DefaultLayout,
      beforeEnter: guest,
      children: [
        {
          path: 'register',
          name: 'Register',
          component: RegisterView,
        },
        {
          path: 'login',
          name: 'Login',
          component: LoginView,
        },
      ],
    },
    {
      path: '/',
      component: AuthLayout,
      beforeEnter: auth,
      children: [
        {
          path: 'checkout',
          name: 'Checkout',
          component: CheckoutView
        },
        {
          path: 'orders',
          name: 'Orders',
          component: OrdersView,
        },
      ],
    },
    {
      path: '/:pathMatch(.*)*',
      name: 'NotFound',
      component: NotFoundView
    },
    // {
    //   path: '/user-:afterUser(.*)',
    //   component: UserGeneric
    // },
  ],
});

export default router;
