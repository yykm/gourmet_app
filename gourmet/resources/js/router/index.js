import Vue from 'vue';
import VueRouter from 'vue-router';
import store from './../store';
import Home from '../views/Home.vue';
import Login from '../views/Login.vue';
import Register from '../views/Register.vue';
import Reset from '../views/Reset.vue';
import SystemError from '../views/Err/SystemError.vue';

Vue.use(VueRouter);

// 認証状態で認証済ページへ遷移時にガード
const authGuard = function (to, from, next) {
  if (store.getters['App/isLogin']) {
    // 認証済みならホームへ
    next('/');
  } else {
    // そうでなければ遷移先へ
    next();
  }
}

const routes = [
  // top
  {
    path: '/',
    name: 'Home',
    component: Home,
    beforeEnter(to, from, next){
      store.dispatch('App/updateShops', null);
      next();
    }
  },
  // ログイン
  {
    path: '/login',
    name: 'Login',
    component: Login,
    beforeEnter: authGuard
  },
  // 新規登録
  {
    path: '/register',
    name: 'Register',
    component: Register,
    beforeEnter: authGuard
  },
  // パスワードリセット
  {
    path: '/reset',
    name: 'Reset',
    component: Reset,
    beforeEnter: authGuard
  },
  // 500エラー
  {
    path: '/500',
    component: SystemError
  }
];

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
});

export default router;
