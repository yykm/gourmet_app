import { ERR } from './const';

/**
 * モジュールストア:Err
 * エラーに関するデータ及び操作を定義
*/
export default {
  namespaced: true,
  state: {
    code: null
  },

  getters: {
    [ERR.GET_CODE] (state) {
      return state.code;
    }
  },

  mutations: {
    [ERR.SET_CODE] (state, code) {
      state.code = code
    }
  },

  actions: {
    [ERR.SET_CODE] ({commit}, code) {
      commit(ERR.SET_CODE, code);
    }
  }
}
