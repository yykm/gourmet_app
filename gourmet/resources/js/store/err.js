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
    getCode(state) {
      return state.code;
    },
    getLoginErrorMessage(state) {
      if (state.LoginErrorMessage) {
        return ' ログインID、またはパスワードが間違っています。';
      };

      return null;
    },
    getRegisterErrorMessage(state) {
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
    setCode(state, code) {
      state.code = code;
    },
    setLoginErrorMessage(state, message) {
      state.LoginErrorMessage = message;
    },
    setRegisterErrorMessage(state, message) {
      state.RegisterErrorMessage = message;
    },
  },

  actions: {
    setCode({
      commit
    }, code) {
      commit('setCode', code);
    },
    setLoginErrorMessage({
      commit
    }, message) {
      commit('setLoginErrorMessage', message);
    },
    setRegisterErrorMessage({
      commit
    }, message) {
      commit('setRegisterErrorMessage', message);
    }
  }
}
