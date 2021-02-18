import Vue from 'vue';
import VueRouter from 'vue-router';
import store from './../store';
import Home from '../views/Home.vue';
import Login from '../views/Login.vue';
import Register from '../views/Register.vue';
// import Reset from '../views/Reset.vue';
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

// ログイン状態で要ログインページへ遷移時にガード
const Authorized = function (to, from, next) {
  if (store.getters['App/isLogin']) {
    // ログイン済みならホームへ
    next('/');
  } else {
    // そうでなければ遷移先へ
    next();
  }
}

// 未ログイン状態で要ログインページへ遷移時にガード
const notAuthorized = function (to, from, next) {
  if (!store.getters['App/isLogin']) {
    // 未ログインならホームへ
    next('/');
  } else {
    // そうでなければ遷移先へ
    next();
  }
}

// 検索してストアに店舗情報がない状態で店舗詳細ページへ遷移した場合にガード
const notSearched = function (to, from, next) {
  if (!store.getters['App/getShops']) {
    // ストアに店舗情報が無い場合はホームへ
    next('/');
  } else {
    // そうでなければ遷移先へ
    next();
  }
}

const routes = [
  // ホーム
  {
    path: '/',
    name: 'Home',
    component: Home,
    beforeEnter(to, from, next) {
      store.dispatch('App/updateShops', null);
      next();
    }
  },
  // 検索結果
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
  /* パスワードリセット（未実装のためコメントアウト）
  {
    path: '/reset',
    name: 'Reset',
    component: Reset,
    beforeEnter: Authorized
  },
  */
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
    path: '/detail/:shop_id',
    name: 'detail',
    component: Detail,
    beforeEnter: notSearched,
    props: (route) => ({
      tab: Number(route.query.tab),
      shop_id: String(route.params.shop_id)
    }),
    // 店舗詳細ページ
    children: [{
      path: 'info',
      name: 'info',
      components: {
        content: Info
      }
    },
    // 写真投稿ページ
    {
      path: 'photoList',
      name: 'photoList',
      components: {
        content: PhotoList
      },
    },
    // 口コミ投稿ページ
    {
      path: 'review',
      name: 'review',
      components: {
        content: ReviewList
      }
    },
    // アクセス情報ページ
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
  mode: 'history', // historyモード
  base: process.env.BASE_URL, // .envより基底フォルダを取得、設定

  // ページ遷移ごとにページ最上部へ遷移
  scrollBehavior() {
    return {
      x: 0,
      y: 0
    }
  },
  routes // ルート情報読み込み
});

export default router;
