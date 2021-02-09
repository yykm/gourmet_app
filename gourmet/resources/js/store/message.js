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

      // 表示時間が未指定か、1.5秒以下の場合に1.5秒にデフォルト設定
      if (!timeout || timeout < 1500) {
        timeout = 1500;
      }

      state.timeout = Number(timeout);
    }
  },
}
