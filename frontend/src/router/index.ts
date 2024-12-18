import {createRouter, createWebHistory, type RouteLocation} from 'vue-router'
import HomeView from '../views/HomeView.vue'
import RegisterView from "../views/RegisterView.vue";
import OrdersListView from "../views/OrdersListView.vue";
import NotFoundView from "@/views/NotFoundView.vue";
import DefaultLayout from "@/layouts/DefaultLayout.vue";
import AuthLayout from "@/layouts/AuthLayout.vue";
import LoginView from "@/views/LoginView.vue";
import AboutView from "@/views/AboutView.vue";
import TermsAndConditionsView from "@/views/TermsAndConditionsView.vue";
import {useAuthStore} from "@/stores/useAuthStore.ts";

async function auth(to: RouteLocation, from: RouteLocation) {
  const auth = useAuthStore();

  if (!auth.user) {
    return '/login';
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
          name: 'Home',
          component: HomeView,
        },
        {
          path: 'about',
          name: 'About',
          component: AboutView,
        },
        {
          path: 'terms-and-conditions',
          name: 'Terms And Conditions',
          component: TermsAndConditionsView,
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
          path: 'orders',
          name: 'Orders',
          component: OrdersListView,
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
