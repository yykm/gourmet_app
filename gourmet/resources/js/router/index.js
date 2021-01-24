import Vue from 'vue';
import VueRouter from 'vue-router';
import store from './../store';
import Home from '../views/Home.vue';
import Login from '../views/Login.vue';
import Register from '../views/Register.vue';
import Reset from '../views/Reset.vue';
import SystemError from '../views/Err/SystemError.vue';
import Detail from '../views/Detail.vue';
import Info from '../views/Info.vue';
import PhotoList from '../views/PhotoList.vue';
import Review from '../views/Review.vue';

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
    beforeEnter(to, from, next) {
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
  // 店舗詳細
  {
    path: '/detail/:id/:tab',
    name: 'detail',
    component: Detail,
    props: (route) => ({
      tab: Number(route.params.tab),
      id: String(route.params.id)
    }),
    children: [{
        path: 'info',
        name: 'info',
        components: {
          content: Info
        }
      },
      {
        path: 'photoList',
        name: 'photoList',
        components: {
          content: PhotoList
        },
      },
      {
        path: 'review',
        name: 'review',
        components: {
          content: Review
        }
      },
    ]
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
