import { createRouter, createWebHistory } from 'vue-router'
import Login from '@/pages/Login.vue'
import Dashboard from '@/pages/Dashboard.vue'
import AdminUsers from '@/pages/AdminUsers.vue'
import { useUserStore } from '@/store/user'

const routes = [
  { path: '/', redirect: '/user' },
  { path: '/user', component: Login },
  { path: '/dashboard', component: Dashboard, meta: { requiresAuth: true } },
  { path: '/admin/users', component: AdminUsers, meta: { requiresAuth: true } },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach((to, from, next) => {
  const store = useUserStore()

  // Redirect authenticated users away from login
  if (to.path === '/user' && store.isAuthenticated) {
    return next('/dashboard')
  }

  // Redirect unauthenticated users away from protected routes
  if (to.meta.requiresAuth && !store.isAuthenticated) {
    return next('/user')
  }

  next()
})

export default router
