import Vue from 'vue';
import Vuex from 'vuex';
import { UPDATE_SHOPS } from './mutation-types';

Vue.use(Vuex);

export default new Vuex.Store({
  strict: true,

  state: {
    shops: [], // 検索結果
    host: {
      // 基底URL
      baseURL: 'http://ec2-13-230-134-90.ap-northeast-1.compute.amazonaws.com',
      // 相対URL
      relativeURL: {
        search: '/search' // 検索結果の取得用URL
      }
    }
  },

  getters: {
    // 検索結果の取得
    getShops(state) {
      return state.shops;
    },
    // 検索件数の取得
    shopsCount(state) {
      return state.shops.length;
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
    }
  },

  actions: {
    // 検索処理
    [UPDATE_SHOPS]({ commit }, payload) {
      commit(UPDATE_SHOPS, payload);
    }
  }
});
