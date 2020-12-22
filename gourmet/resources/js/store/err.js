import { ERR } from './const';

/**
 * モジュールストア:Err
 * エラーに関するデータ及び操作を定義
*/
export default {
  namespaced: true,
  state: {
    code: null,
    errorMessage: null
  },

  getters: {
    [ERR.GET_CODE] (state) {
      return state.code;
    },
    [ERR.GET_ERROR_MESSAGE] (state) {
      return state.errorMessage;
    }
  },

  mutations: {
    [ERR.SET_CODE] (state, code) {
      state.code = code;
    },
    [ERR.SET_ERROR_MESSAGE] (state, message) {
      state.errorMessage = message;
    },
  },

  actions: {
    [ERR.SET_CODE] ({commit}, code) {
      commit(ERR.SET_CODE, code);
    },
    [ERR.SET_ERROR_MESSAGE] ({commit}, message) {
      commit(ERR.SET_ERROR_MESSAGE, message);
    }
  }
}
