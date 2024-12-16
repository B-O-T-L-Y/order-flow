import {createRouter, createWebHistory, type RouteLocation} from 'vue-router'
import HomeView from '../views/HomeView.vue'
import RegisterView from "../views/RegisterView.vue";
import OrdersView from "../views/OrdersView.vue";
import NotFoundView from "@/views/NotFoundView.vue";
import DefaultLayout from "@/layouts/DefaultLayout.vue";
import AuthLayout from "@/layouts/AuthLayout.vue";
import LoginView from "@/views/LoginView.vue";
import AboutView from "@/views/AboutView.vue";
import TermsAndConditionsView from "@/views/TermsAndConditionsView.vue";
import {useAuthStore} from "@/stores/useAuthStore.ts";

function auth(to: RouteLocation, from: RouteLocation) {
  const auth = useAuthStore();

  if (!auth.user) {
    return '/login';
  }
}

function guest(to: RouteLocation, from: RouteLocation) {
  const auth = useAuthStore();

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
      beforeEnter: guest,
      children: [
        {
          path: '',
          name: 'Home',
          component: HomeView, // HomeView как главная страница
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

router.beforeEach(async (to: RouteLocation, from: RouteLocation) => await useAuthStore().verifySession());

export default router
