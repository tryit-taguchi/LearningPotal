import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

export default new Router({
  mode: 'history',
  base: process.env.BASE_URL,
  routes: [
    {
      path: '/',
      name: 'home',
      component: () => import('./views/Home.vue'),
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('./views/Login.vue'),
    },
    {
      path: '/agreement',
      name: 'agreement',
      component: () => import('./views/Agreement.vue'),
    },
    {
      path: '/agreement',
      name: 'agreement',
      component: () => import('./views/Agreement.vue'),
    },
    {
      path: '/sensebefore1',
      name: 'sensebefore1',
      component: () => import('./views/SenseBefore1.vue')
    },
    {
      path: '/sensebefore2',
      name: 'sensebefore2',
      component: () => import('./views/SenseBefore2.vue')
    },
    {
      path: '/trial1',
      name: 'trial1',
      component: () => import('./views/Trial1.vue')
    },
    {
      path: '/trial2',
      name: 'trial2',
      component: () => import('./views/Trial2.vue')
    },
    {
      path: '/real',
      name: 'real',
      component: () => import('./views/Real.vue')
    },
    {
      path: '/senseafter',
      name: 'senseafter',
      component: () => import('./views/SenseAfter.vue')
    },
    {
      path: '/senselast',
      name: 'senselast',
      component: () => import('./views/SenseLast.vue')
    },
    {
      path: '/catchword',
      name: 'catchword',
      component: () => import('./views/Catchword.vue')
    },
    {
      path: '/faq',
      name: 'faq',
      component: () => import('./views/Faq.vue')
    },
    {
      path: '/understanding',
      name: 'understanding',
      component: () => import('./views/Understanding.vue')
    },
    {
      path: '/enquete',
      name: 'enquete',
      component: () => import('./views/Enquete.vue')
    },
    {
      path: '/sensebefore1result',
      name: 'sensebefore1result',
      component: () => import('./views/SenseBefore1Result.vue')
    },
    {
      path: '*',
      component: () => import('./views/Error404.vue')
    }
  ]
})
