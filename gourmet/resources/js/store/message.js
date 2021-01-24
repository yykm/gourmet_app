/**
 * メッセージストア:Message
 * メッセージ管理用
 */
export default {
  namespaced: true,

  state: {
    content: '',
    timeout: null,
    success: null
  },

  // メッセージと表示時間設定
  mutations: {
    setContent(state, {
      success,
      content,
      timeout
    }) {

      state.success = Boolean(success);
      state.content = content;

      // 表示時間が未指定か、1秒以下の場合に3秒にデフォルト設定
      if (!timeout || timeout < 1000) {
        timeout = 3000;
      }

      state.timeout = Number(timeout);
    }
  },
}
