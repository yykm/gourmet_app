<template> </template>

<script>
import { mapState, mapMutations } from "vuex";

export default {
  computed: {
    ...mapState({
      message: state => state.Message.content,
      timeout: state => state.Message.timeout,
      success: state => state.Message.success
    })
  },
  watch: {
    message(newMsg) {
      if (newMsg) {
        this.toast("b-toaster-top-center");
        // 初期化
        this.setContent({ success: null, content: "", timeout: 3000 });
      }
    }
  },
  methods: {
    ...mapMutations("Message", ["setContent"]),

    toast(toaster, append = false) {
      this.$bvToast.toast(this.message, {
        title: this.success ? "成功" : "失敗",
        toaster,
        solid: true,
        appendToast: append,
        autoHideDelay: this.timeout,
        variant: this.success ? "success" : "danger",
        headerClass: "justify-content-center",
        bodyClass: "text-center",
        noCloseButton: true
      });
    }
  }
};
</script>
