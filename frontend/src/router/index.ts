import { createRouter, createWebHistory } from 'vue-router'
import adminLayout from '@/views/layout/adminLayout.vue'
import homePage from '@/views/HomePage.vue'
import loginPage from '@/views/login/IndexPage.vue'
import sellerPage from '@/views/seller/IndexPage.vue'
import salePage from '@/views/sale/IndexPage.vue'
import { useAuthStore } from '@/stores/auth'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    { 
      path: '/login', 
      component: loginPage
    },
    {
      path: '/',
      component: adminLayout, // opcional
      meta: { requiresAuth: true },
      children: [
        { 
          path: '/', 
          component: homePage 
        },
        { 
          path: '/sellers', 
          component: sellerPage 
        },
        { 
          path: '/sales', 
          component: salePage 
        }
      ]
    }
  ],
})

router.beforeEach(async (to, from, next) => {
  const auth = useAuthStore();
  
  await auth.refreshUser();

  if (to.meta.requiresAuth && !auth.isAuthenticated) {
    return next('/login');
  }

  next();
});

export default router
