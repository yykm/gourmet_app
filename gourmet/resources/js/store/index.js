import Vue from 'vue';
import Vuex from 'vuex';
import {
  UPDATE_SHOPS,
  SET_USER
} from './mutation-types';

Vue.use(Vuex);

export default new Vuex.Store({
  strict: true,
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
      }
    }
  },

  getters: {
    // 検索結果の取得
    getShops(state) {
      return state.shops;
    },
    isLogin(state) {
      return state.user ?? false;
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
    [UPDATE_SHOPS]({ commit }, payload) {
      commit(UPDATE_SHOPS, payload);
    },
    // 新規登録API
    async [SET_USER]({ commit }, data) {
      const response = await axios.post(
        this.getters.getURLs.prefix +
        this.getters.getURLs.rel.register,
        data
      );
      commit(SET_USER, response.data);
    },
    // ログインAPI
    async login({ commit }, data) {
      const response = await axios.post(
        this.getters.getURLs.prefix +
        this.getters.getURLs.rel.login,
        data
      );
      commit(SET_USER, response.data);
    },
    // ログアウトAPI
    async logout({ commit }) {
      const response = await axios.post(
        this.getters.getURLs.prefix +
        this.getters.getURLs.rel.logout
      );
      commit(SET_USER, null);
    }
  },
});
