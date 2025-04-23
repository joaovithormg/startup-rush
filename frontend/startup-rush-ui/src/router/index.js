import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  {
    path: '/',
    name: 'Home',
    component: () => import('../views/Home.vue')
  },
  {
    path: '/startups',
    name: 'Startups',
    component: () => import('../views/Startups.vue')
  },
  {
    path: '/torneio',
    name: 'Torneio',
    component: () => import('../views/Torneio.vue')
  },
  {
    path: '/batalhas',
    name: 'Batalhas',
    component: () => import('../views/Batalhas.vue')
  },
  {
    path: '/relatorios',
    name: 'Relatorios',
    component: () => import('../views/Relatorios.vue')
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router