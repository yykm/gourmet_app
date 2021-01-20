import { APP, ERR } from './const';

/**
 * モジュールストア:App
 * アプリに関するデータ及び操作を定義
*/
export default {
  namespaced: true,
  state: {
    shops: null, // 検索結果
    apiStatus: null,// API通信結果
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
    [APP.GET_SHOPS](state) {
      return state.shops;
    },
    // API通信結果の取得
    [APP.GET_API_STATUS](state) {
      return state.apiStatus;
    },
    // 認証状態
    [APP.IS_LOGIN](state) {
      return !! state.user;
    },
    // ユーザ名
    [APP.USER_NAME](state) {
      return state.user ? state.user.name : '';
    },
    // 検索件数の取得
    [APP.SHOPS_COUNT](state) {
      if (state.shops === null) {
        return null;
      } else {
        return state.shops.length;
      }
    },
    // ページングごとのページ取得
    [APP.GET_SHOPS_BY_PAGE](state) {
      return (curPage, perPage) => {
        return state.shops === null ? [] : state.shops.slice(
          (curPage - 1) * perPage,
          (curPage - 1) * perPage + perPage
        );
      };
    },
    // URLの取得
    [APP.GET_URLS](state) {
      return(key) => {
        const prefix = state.host.prefix;
        let url = '';

        switch (key) {
          case 'search':
            url = (prefix + state.host.rel.search);
            break;
          case APP.REGISTER:
            url = (prefix + state.host.rel.register);
            break;
          case APP.LOGIN:
            url = (prefix + state.host.rel.login);
            break;
          case APP.LOGOUT:
            url = (prefix + state.host.rel.logout);
            break;
          case APP.CURRET_USER:
            url = (prefix + state.host.rel.user);
            break;
          default:
            url = ''
        }
        return url;
      }
    }
  },

  mutations: {
    // 検索結果(shops)の更新
    [APP.UPDATE_SHOPS](state, shops) {
      state.shops = shops;
    },
    // 認証済みユーザ更新
    [APP.SET_USER] (state, user) {
      state.user = user;
    },
    [APP.SET_API_STATUS] (state, status) {
      state.apiStatus = status;
    }
  },

  actions: {
    // 検索処理
    [APP.UPDATE_SHOPS]({commit}, payload) {
      commit(APP.UPDATE_SHOPS, payload);
    },

    // 新規登録API
    async [APP.REGISTER]({getters, commit, dispatch}, data) {
      // URI取得
      const url = getters[APP.GET_URLS](APP.REGISTER);

      // リクエスト発行
      const response = await axios.post(url, data)
      .catch(err => err.response || err);

      // 通信成功（ユーザが作成された）場合
      if (response.status === ERR.CREATED) {
        commit(APP.SET_API_STATUS, true);
        commit(APP.SET_USER, response.data);
        return;
      }

      // 通信失敗（ユーザが作成された）場合
      commit(APP.SET_API_STATUS, false);
      // バリデーション
      if (response.status === ERR.UNPROCESSABLE_ENTITY) {
        dispatch(ERR.getErrURI(ERR.SET_REGISTER_ERROR_MESSAGE), response.data.errors, { root: true } );
      } else {
      // 内部エラー
        dispatch(ERR.getErrURI(ERR.SET_CODE), response.status, { root: true });
      }
    },

    // ログインAPI
    async [APP.LOGIN]({getters, commit, dispatch}, data) {
      commit(APP.SET_API_STATUS, null);
      // URI取得
      const url = getters[APP.GET_URLS](APP.LOGIN);

      // リクエスト発行
      const response = await axios.post(url, data)
      .catch(err => err.response || err);

      // API通信成功時
      if (response.status === ERR.OK) {
        commit(APP.SET_API_STATUS, true);
        commit(APP.SET_USER, response.data);
        return;
      }

      // API通信失敗時
      commit(APP.SET_API_STATUS, false);
      // 認証失敗エラー
      if (response.status === ERR.UNPROCESSABLE_ENTITY){
        dispatch(ERR.getErrURI(ERR.SET_LOGIN_ERROR_MESSAGE), response.data.errors, { root: true });
      } else {
      // 内部エラー
        dispatch(ERR.getErrURI(ERR.SET_CODE), response.status, { root: true });
      }
    },

    // ログアウトAPI
    async [APP.LOGOUT]({getters, commit, dispatch}) {
      commit(APP.SET_API_STATUS, null);
      // URI取得
      const url = getters[APP.GET_URLS](APP.LOGOUT);

      // リクエスト発行
      const response = await axios.post(url)
      .catch(err => err.response || err);

      // API通信成功時
      if (response.status === ERR.OK) {
        commit(APP.SET_API_STATUS, true);
        commit(APP.SET_USER, null);
        return;
      }

      // 内部エラー
      commit(APP.SET_API_STATUS, false);
      dispatch(ERR.getErrURI(ERR.SET_CODE), response.status, { root: true });
    },
    // ログインユーザ取得API
    async [APP.CURRET_USER] ({getters, commit, dispatch}) {
      commit(APP.SET_API_STATUS, null);

      // URI取得
      const url = getters[APP.GET_URLS](APP.CURRET_USER);

      // リクエスト発行
      const response = await axios.get(url)
      .catch(err => err.response || err);

      // API通信成功時
      if (response.status === ERR.OK) {
        const user = (response.data === '' ? null : response.data);
        commit(APP.SET_USER, user);
        return;
      }

      // 内部エラー
      commit(APP.SET_API_STATUS, false);
      dispatch(ERR.getErrURI(ERR.SET_CODE), response.status, { root: true });
    }
  },
}
