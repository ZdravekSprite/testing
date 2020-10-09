import Vue from 'vue'
import Router from 'vue-router'
import Home from './views/Home'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'home',
      component: Home
    },
    {
      path: '/galerija',
      name: 'gallery',
      component: () => import('./views/Gallery')
    },
    {
      path: '/kontakt',
      name: 'contact',
      component: () => import('./views/Contact')
    }
  ]
})
