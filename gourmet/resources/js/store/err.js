import { ERR } from './const';

/**
 * モジュールストア:Err
 * エラーに関するデータ及び操作を定義
 */
export default {
  namespaced: true,
  state: {
    code: null,
    LoginErrorMessage: null,
    RegisterErrorMessage: null
  },

  getters: {
    [ERR.GET_CODE](state) {
      return state.code;
    },
    [ERR.GET_LOGIN_ERROR_MESSAGE](state) {
      if (state.LoginErrorMessage) {
        return ' ログインID、またはパスワードが間違っています。';
      };

      return null;
    },
    [ERR.GET_REGISTER_ERROR_MESSAGE](state) {
      if (state.RegisterErrorMessage) {

        let message = '';
        switch (Object.keys(state.RegisterErrorMessage)[0]) {
          case 'name':
            message = '入力されたお名前は既に登録済みです。'
            break;
          case 'email':
            message = '入力されたメールアドレスは既に登録済みです。'
            break;
          default:
            message = null;
        }
        return message;
      }

      return null;
    }
  },

  mutations: {
    [ERR.SET_CODE](state, code) {
      state.code = code;
    },
    [ERR.SET_LOGIN_ERROR_MESSAGE](state, message) {
      state.LoginErrorMessage = message;
    },
    [ERR.SET_REGISTER_ERROR_MESSAGE](state, message) {
      state.RegisterErrorMessage = message;
    },
  },

  actions: {
    [ERR.SET_CODE]({
      commit
    }, code) {
      commit(ERR.SET_CODE, code);
    },
    [ERR.SET_LOGIN_ERROR_MESSAGE]({
      commit
    }, message) {
      commit(ERR.SET_LOGIN_ERROR_MESSAGE, message);
    },
    [ERR.SET_REGISTER_ERROR_MESSAGE]({
      commit
    }, message) {
      commit(ERR.SET_REGISTER_ERROR_MESSAGE, message);
    }
  }
}
