import Vue from 'vue';
import VueRouter from 'vue-router';
import store from './../store';
import Home from '../views/Home.vue';
import Login from '../views/Login.vue';
import Register from '../views/Register.vue';
import Reset from '../views/Reset.vue';
import Detail from '../views/Detail.vue';
import Info from '../views/Info.vue';
import PhotoList from '../views/PhotoList.vue';
import ReviewList from '../views/ReviewList.vue';
import Access from '../views/Access.vue';
import Result from '../views/Result.vue';
import Reserved from '../views/Reserved.vue';
import Mypage from '../views/Mypage.vue';
import Thanks from '../views/Thanks.vue';
import SystemError from '../views/Err/SystemError.vue';
import NotFound from '../views/Err/NotFound.vue';

Vue.use(VueRouter);

// 認証状態で認証済ページへ遷移時にガード
const Authorized = function (to, from, next) {
  if (store.getters['App/isLogin']) {
    // 認証済みならホームへ
    next('/');
  } else {
    // そうでなければ遷移先へ
    next();
  }
}

// 認証されていない状態で要認証ページへ遷移時にガード
const notAuthorized = function (to, from, next) {
  if (!store.getters['App/isLogin']) {
    // 認証済みならホームへ
    next('/');
  } else {
    // そうでなければ遷移先へ
    next();
  }
}

// 検索してストアに店舗情報がない状態で店舗詳細ページへ遷移した場合にガード
const notSearched = function (to, from, next) {
  if (!store.getters['App/getShops']) {
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
  {
    path: '/result',
    name: 'Result',
    component: Result,
    beforeEnter: notSearched
  },
  // ログイン
  {
    path: '/login',
    name: 'Login',
    component: Login,
    beforeEnter: Authorized
  },
  // 新規登録
  {
    path: '/register',
    name: 'Register',
    component: Register,
    beforeEnter: Authorized
  },
  // パスワードリセット
  {
    path: '/reset',
    name: 'Reset',
    component: Reset,
    beforeEnter: Authorized
  },
  // 予約完了ページ
  {
    path: '/reserved',
    name: 'Reserved',
    component: Reserved,
    props: (route) => ({
      reservation: Object(route.query.reservation)
    }),
    beforeEnter: notAuthorized
  },
  // マイページ
  {
    path: '/mypage',
    name: 'Mypage',
    component: Mypage,
    props: (route) => ({
      tab: String(route.query.tab)
    }),
    beforeEnter: notAuthorized
  },
  // 店舗詳細
  {
    path: '/detail/:id/:tab',
    name: 'detail',
    component: Detail,
    beforeEnter: notSearched,
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
        content: ReviewList
      }
    },
    {
      path: 'access',
      name: 'access',
      components: {
        content: Access
      }
    },
    ]
  },
  // 退会完了ページ
  {
    path: '/thanks',
    name: 'thanks',
    component: Thanks,
  },
  // 500エラー
  {
    path: '/500',
    component: SystemError
  },
  // 404エラー
  {
    path: '*',
    component: NotFound
  }
];

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  scrollBehavior() {
    return {
      x: 0,
      y: 0
    }
  },
  routes
});

export default router;
