import Vue from 'vue';
import VueRouter from 'vue-router';
import store from './../store';
import Home from '../views/Home.vue';
import Login from '../views/Login.vue';
import Register from '../views/Register.vue';
import Reset from '../views/Reset.vue';

Vue.use(VueRouter);

// 認証状態で認証済ページへ遷移時にガード
const authGuard = function (to, from, next) {
  if (store.getters['isLogin']) {
    // 認証済みならホームへ
    next('/');
  } else {
    // そうでなければ遷移先へ
    next();
  }
}

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/login',
    name: 'Login',
    component: Login,
    beforeEnter: authGuard
  },
  {
    path: '/register',
    name: 'Register',
    component: Register,
    beforeEnter: authGuard
  },
  {
    path: '/reset',
    name: 'Reset',
    component: Reset,
    beforeEnter: authGuard
  }
];

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
});

export default router;
