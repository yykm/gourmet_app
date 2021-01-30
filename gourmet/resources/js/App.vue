<template>
  <div id="app">
    <Message />
    <router-view></router-view>
  </div>
</template>

<script>
import Message from "./components/Message.vue";
import { ERR } from "./store/const.js";
import { createNamespacedHelpers } from "vuex";
const { mapGetters, mapActions } = createNamespacedHelpers(ERR.STORE);

export default {
  name: "App",
  components: {
    Message
  },
  computed: {
    ...mapGetters([ERR.GET_CODE])
  },
  methods: {
    ...mapActions([ERR.SET_CODE])
  },
  watch: {
    [ERR.GET_CODE]: {
      handler(code) {
        switch (code) {
          // 500エラー
          case ERR.INTERNAL_SERVER_ERROR:
            this.$router.push("/500");
            break;
        }
      },
      immediate: true
    },
    $route() {
      this[ERR.SET_CODE](null);
    }
  }
};
</script>

<style>
body {
  background-color: #f8f9fa !important;
}
</style>
