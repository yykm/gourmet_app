import { STATUS } from './../util.js';

/**
 * モジュールストア:App
 * アプリに関するデータ及び操作を定義
 */
export default {
  namespaced: true,
  state: {
    shops: null, // 検索結果
    apiStatus: null, // API通信結果
    user: null, // 認証済みユーザ
  },

  getters: {
    // 店舗の検索結果の取得
    getShops(state) {
      return state.shops;
    },
    // 特定の店舗情報の取得
    getShop(state) {
      return (shopId) => {
        return state.shops ? state.shops.find(shop => shop.id === shopId) : null;
      };
    },
    // 店舗検索結果の件数の取得
    shopsCount(state) {
      if (state.shops === null) {
        return null;
      } else {
        return state.shops.length;
      }
    },
    // API通信結果の取得
    getApiStatus(state) {
      return state.apiStatus;
    },
    // ログイン状態
    isLogin(state) {
      return !!state.user;
    },
    // ログインユーザ名
    userName(state) {
      return state.user ? state.user.name : '';
    },
    // ユーザ情報
    getUser(state) {
      return state.user ? state.user : '';
    },

    // ページングのページごとの対応する店舗情報取得
    getShopsByPage(state) {
      return (curPage, perPage) => {
        return state.shops === null ? [] : state.shops.slice(
          (curPage - 1) * perPage,
          (curPage - 1) * perPage + perPage
        );
      };
    },
  },

  mutations: {
    // 検索結果(shops)の更新
    updateShops(state, shops) {
      state.shops = shops;
    },
    // 認証済みユーザ更新
    setUser(state, user) {
      state.user = user;
    },
    setApiStatus(state, status) {
      state.apiStatus = status;
    }
  },

  actions: {
    // 検索処理
    updateShops({
      commit
    }, payload) {
      commit('updateShops', payload);
    },

    // 新規登録API
    async register({
      commit,
      dispatch
    }, data) {

      // リクエスト発行
      const response = await axios.post('/api/register', data)
        .catch(err => err.response || err);

      // 通信成功（ユーザが作成された）場合
      if (response.status === STATUS.CREATED) {
        commit('setApiStatus', true);
        commit('setUser', response.data);
        return;
      }

      // 通信失敗（ユーザが作成された）場合
      commit('setApiStatus', false);
      // バリデーション
      if (response.status === STATUS.UNPROCESSABLE_ENTITY) {
        dispatch('Err/setRegisterErrorMessage', response.data.errors, {
          root: true
        });
      } else {
        // 内部エラー
        dispatch('Err/setCode', response.status, {
          root: true
        });
      }
    },

    // ログインAPI
    async login({
      commit,
      dispatch
    }, data) {
      commit('setApiStatus', null);

      // リクエスト発行
      const response = await axios.post('/api/login', data)
        .catch(err => err.response || err);

      // API通信成功時
      if (response.status === STATUS.OK) {
        commit('setApiStatus', true);
        commit('setUser', response.data);

        // 成功メッセージ
        commit('Message/setContent', {
          success: true,
          content: "ログインしました",
          timeout: 1500,
        }, {
          root: true
        });
        return;
      }

      // API通信失敗時
      commit('setApiStatus', false);
      // 認証失敗エラー
      if (response.status === STATUS.UNPROCESSABLE_ENTITY) {
        dispatch('Err/setLoginErrorMessage', response.data.errors, {
          root: true
        });
      } else {
        // 内部エラー
        dispatch('Err/setCodes', response.status, {
          root: true
        });
      }
    },

    // ログアウトAPI
    async logout({
      commit,
      dispatch
    }) {

      // リクエスト発行
      const response = await axios.post('/api/logout')
        .catch(err => err.response || err);

      // API通信成功時
      if (response.status === STATUS.OK) {
        commit('setApiStatus', true);
        commit('setUser', null);

        // 成功メッセージ
        commit('Message/setContent', {
          success: true,
          content: "ログアウトしました",
          timeout: 1500,
        }, {
          root: true
        });
        return;
      }

      // 内部エラー
      commit('setApiStatus', false);
      dispatch('Err/setCode', response.status, {
        root: true
      });
    },

    // ログインユーザ取得API
    async currentUser({
      commit,
      dispatch
    }) {

      // リクエスト発行
      const response = await axios.get('/api/user')
        .catch(err => err.response || err);

      // API通信成功時
      if (response.status === STATUS.OK) {
        const user = (response.data === '' ? null : response.data);
        commit('setUser', user);
        return;
      }

      // 内部エラー
      commit('setApiStatus', false);
      dispatch('Err/setCode', response.status, {
        root: true
      });
    }
  },
}
