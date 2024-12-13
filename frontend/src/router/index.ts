import {createRouter, createWebHistory} from 'vue-router'
import HomeView from '../views/HomeView.vue'
import RegisterView from "../views/RegisterView.vue";
import OrdersView from "../views/OrdersView.vue";
import NotFoundView from "@/views/NotFoundView.vue";
import DefaultLayout from "@/layouts/DefaultLayout.vue";
import AuthLayout from "@/layouts/AuthLayout.vue";
import LoginView from "@/views/LoginView.vue";
import AboutView from "@/views/AboutView.vue";
import TermsAndConditionsView from "@/views/TermsAndConditionsView.vue";

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
          component: HomeView, // HomeView как главная страница
        },
        {
          path: 'about',
          name: 'About',
          component: AboutView,
        },
        {
          path: 'orders',
          name: 'Orders',
          component: OrdersView,
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
      component: AuthLayout,
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
      path: '/:pathMatch(.*)*',
      name: 'NotFound',
      component: NotFoundView
    },
    // {
    //   path: '/user-:afterUser(.*)',
    //   component: UserGeneric
    // },
  ],
})

export default router
