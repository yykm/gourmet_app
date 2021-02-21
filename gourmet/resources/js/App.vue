<template>
  <div id="app">
    <!-- 全ページ共通の通知用トーストコンポーネント -->
    <Message />
    <!-- ページコンポーネントのレンダリング部 -->
    <router-view></router-view>
    <!-- 全ページ共通の画面右下に表示されるページ上部へのスクロールボタン -->
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
import { STATUS } from "./util.js";
import { createNamespacedHelpers } from "vuex";
const { mapGetters, mapActions } = createNamespacedHelpers("Err");
import _ from "lodash";

export default {
  name: "App",
  components: {
    Message,
  },
  data() {
    return {
      scrolled: 0, // 画面Y方向のスクロール量
    };
  },
  computed: {
    ...mapGetters(['getCode']),

    // 400px以上の移動でスクロールボタンを表示
    isChange() {
      return this.scrolled > 400;
    },
  },
  methods: {
    ...mapActions(['setCode']),

    // 画面Ｙ方向最上部へ移動
    toTop() {
      window.scroll({
        top: 0,
        behavior: "smooth",
      });
    },

    // 画面Y方向のスクロール量を取得
    getPageYOffset() {
      this.scrolled = window.pageYOffset;
    },
  },
  created() {
    // 遅延関数で連続するイベントをキャンセル
    // 400msごとにY方向のスクロール量をスクロール毎に取得
    this.delayFunc = _.debounce(this.getPageYOffset, 400);
    window.addEventListener("scroll", this.delayFunc);
  },
  watch: {
    // Errストアで保持しているエラーコード値を監視
    // 変化があればコード値によって対応するエラーハンドリングを行う
    getCode: {
      async handler(code) {
        if (!code) return;

        switch (code) {
          // 500エラー
          case STATUS.INTERNAL_SERVER_ERROR:
            this.$router.push("/500");
            break;

          // 認証エラー
          case STATUS.UNAUTHORIZED:
            // CSRFトークンをリフレッシュ
            await axios.get("/api/refresh-token");
            // ログイン状態を初期化
            this.$store.commit("App/setUser", null);
            // ログインページへ遷移
            this.$router.push("/login");

          // 404エラー
          case STATUS.NOT_FOUND:
            // not foundページへ遷移
            this.$router.push("/not-found");
            break;
        }

        this.setCode(null);
      },
      immediate: true,
    },
  },
};
</script>

<style>
body {
  background-color: rgb(150 174 183 / 4%) !important;
  font-family: "M PLUS Rounded 1c" !important;
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
