import { UPDATE_SHOPS, SET_USER } from './mutation-types';

/**
 * モジュールストア:App
 * アプリに関するデータ及び操作を定義
*/
export default {
  namespaced: true,
  state: {
    shops: null, // 検索結果
    user: null, // 認証済みユーザ
    host: {
      // 基底URL
      // base: 'http://ec2-13-114-9-67.ap-northeast-1.compute.amazonaws.com',
      // プレフィックス
      prefix: '/api',
      // 相対URL
      rel: {
        search: '/search', // 検索結果の取得用URL
        register: '/register', // ユーザ登録
        login: '/login', // ログイン
        logout: '/logout', // ログアウト
        user: '/user', // ログインユーザ
      }
    }
  },

  getters: {
    // 検索結果の取得
    getShops(state) {
      return state.shops;
    },
    // 認証状態
    isLogin(state) {
      return !! state.user;
    },
    // ユーザ名
    userName(state) {
      return state.user ? state.user.name : '';
    },
    // 検索件数の取得
    shopsCount(state) {
      if (state.shops === null) {
        return null;
      } else {
        return state.shops.length;
      }
    },
    // ページングごとのページ取得
    getShopsByPage(state) {
      return (curPage, perPage) => {
        return state.shops === null ? [] : state.shops.slice(
          (curPage - 1) * perPage,
          (curPage - 1) * perPage + perPage
        );
      };
    },
    // URLの取得
    getURLs(state) {
      return state.host;
    }
  },

  mutations: {
    // 検索結果(shops)の更新
    [UPDATE_SHOPS](state, payload) {
      state.shops = payload;
    },
    // 認証済みユーザ更新
    [SET_USER] (state, user) {
      state.user = user;
    }
  },

  actions: {
    // 検索処理
    [UPDATE_SHOPS](context, payload) {
      context.commit(UPDATE_SHOPS, payload);
    },
    // 新規登録API
    async [SET_USER](context, data) {
      const response = await axios.post(
        context.getters.getURLs.prefix +
        context.getters.getURLs.rel.register,
        data
      );
      context.commit(SET_USER, response.data);
    },
    // ログインAPI
    async login(context, data) {
      const response = await axios.post(
        context.getters.getURLs.prefix +
        context.getters.getURLs.rel.login,
        data
      );
      context.commit(SET_USER, response.data);
    },
    // ログアウトAPI
    async logout(context) {
      const response = await axios.post(
        context.getters.getURLs.prefix +
        context.getters.getURLs.rel.logout
      );
      context.commit(SET_USER, null);
    },
    // ログインユーザ取得API
    async currentUser (context) {

      const response = await axios.get(
        context.getters.getURLs.prefix +
        context.getters.getURLs.rel.user
      );
      const user = (response.data === '' ? null : response.data);
      console.log(response.data);
      context.commit(SET_USER, user);
    }
  },
}
