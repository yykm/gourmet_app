<template>
  <div id="app">
    <Message />
    <router-view></router-view>
    <b-button id="fixed__btn" @click.prevent="toTop" v-show="isChange"
      ><svg
        xmlns="http://www.w3.org/2000/svg"
        width="30"
        height="30"
        fill="currentColor"
        class="bi bi-chevron-up"
        viewBox="0 0 16 16"
      >
        <path
          fill-rule="evenodd"
          d="M7.646 4.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1-.708.708L8 5.707l-5.646 5.647a.5.5 0 0 1-.708-.708l6-6z"
        />
      </svg>
    </b-button>
  </div>
</template>

<script>
import Message from "./components/Message.vue";
import { ERR } from "./store/const.js";
import { createNamespacedHelpers } from "vuex";
const { mapGetters, mapActions } = createNamespacedHelpers(ERR.STORE);
import _ from "lodash";

export default {
  name: "App",
  components: {
    Message,
  },
  data() {
    return {
      scrolled: 0,
    };
  },
  computed: {
    ...mapGetters([ERR.GET_CODE]),

    isChange() {
      return this.scrolled > 400;
    },
  },
  methods: {
    ...mapActions([ERR.SET_CODE]),

    toTop() {
      window.scroll({
        top: 0,
        behavior: "smooth",
      });
    },

    // スクロール量を取得
    getPageYOffset() {
      this.scrolled = window.pageYOffset;
    },
  },
  created() {
    // 4000msごとにスクロール量をスクロール毎に取得
    this.delayFunc = _.debounce(this.getPageYOffset, 400);
    window.addEventListener("scroll", this.delayFunc);
  },
  watch: {
    [ERR.GET_CODE]: {
      async handler(code) {
        switch (code) {
          // 500エラー
          case ERR.INTERNAL_SERVER_ERROR:
            this.$router.push("/500");
            break;
          // 認証エラー
          case ERR.UNAUTHORIZED:
            // トークンをリフレッシュ
            await axios.get("/api/refresh-token");
            // ストアのuserをクリア
            this.$store.commit("App/setUser", null);
            // ログイン画面へ
            this.$router.push("/login");
          // 500エラー
          case ERR.NOT_FOUND:
            this.$router.push("/not-found");
            break;
        }
      },
      immediate: true,
    },
    $route() {
      this[ERR.SET_CODE](null);
    },
  },
};
</script>

<style>
body {
  background-color: rgb(150 174 183 / 4%) !important;
}

#fixed__btn {
  position: fixed;
  right: 50px;
  bottom: 50px;
  opacity: 0.7;
  transition: 0.5s;
}

#fixed__btn:hover {
  opacity: 1;
}

@media (max-width: 767.98px) {
  #fixed__btn {
    right: 20px;
    bottom: 20px;
  }
}
</style>
