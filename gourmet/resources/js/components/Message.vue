<!-- 通知コンポーネント-->
<template></template>

<script>
import { mapState, mapMutations } from "vuex";

export default {
  computed: {
    ...mapState("Message", ["success", "content", "timeout"]),
  },
  watch: {
    // Messageストアの通知内容を監視し、変化があれば通知処理
    content(newMsg) {
      if (newMsg) {
        this.toast("b-toaster-top-center");
        // 初期化
        this.setContent({ success: null, content: "", timeout: 3000 });
      }
    },
  },
  methods: {
    ...mapMutations("Message", ["setContent"]),

    // 通知処理本体
    toast(toaster, append = false) {
      this.$bvToast.toast(this.content, {
        title: this.success ? "成功" : "失敗", // タイトル
        toaster: "b-toaster-top-center", // トースターのレンタリングの対象
        solid: true, // 半透明か無地のトースター指定
        appendToast: append, // 既に表示されているトースターの下部に更に追加表示するか否か
        autoHideDelay: this.timeout, // トースターが消えるまでの時間指定
        variant: this.success ? "success" : "danger", // トースターのテーマ
        headerClass: "justify-content-center", // ヘッダーに付与するクラス名
        bodyClass: "text-center", // ボディーに付与するクラス名
        noCloseButton: true, // 閉じるボタン表示有無
      });
    },
  },
};
</script>
