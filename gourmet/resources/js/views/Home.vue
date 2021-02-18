<template>
  <!-- ホーム画面 -->
  <b-jumbotron fluid container-fluid>
    <div class="heading-wrapper">
      <div class="heading text-center">
        <!-- サイト名 -->
        <h1>{{ site_name }}</h1>

        <!-- リード文 -->
        <p>
          <slot>お近くの飲食店をフリーワードで検索</slot>
        </p>
      </div>
    </div>

    <!-- 未ログイン状態では「ログイン」「新規登録」ボタン表示 -->
    <div v-if="!isLogin" class="access__area text-center">
      <b-link to="/login">ログイン</b-link>
      <span>｜</span>
      <b-link to="/register">新規登録</b-link>
    </div>

    <!-- ログイン状態では「マイページ」「ログアウト」ボタン表示 -->
    <div v-else class="access__area text-center">
      <b-link to="/mypage">マイページ</b-link>
      <span>｜</span>
      <b-link @click.prevent="logout">ログアウト</b-link>
    </div>

    <!-- 検索フォーム -->
    <Search></Search>
  </b-jumbotron>
</template>

<script>
import Search from "./../components/Search.vue";
import { mapGetters } from "vuex";

export default {
  name: "Home",
  components: {
    Search,
  },
  data() {
    return {
      site_name: process.env.MIX_APP_NAME, // 環境変数よりアプリ名取得
    };
  },
  computed: {
    ...mapGetters("App", ["getApiStatus"]),

    // ログイン状態を取得
    isLogin() {
      return this.$store.getters["App/isLogin"];
    },
  },
  methods: {
    // ログアウト処理
    async logout() {
      await this.$store.dispatch("App/logout");

      // ログアウト処理が成功すればログイン画面へ遷移
      if (this.getApiStatus) {
        this.$router.push("/login");
      }
    },
  },
};
</script>

<style scoped>
.jumbotron .container-fluid {
  background: rgba(255, 255, 255, 0.3);
  height: 100%;
  width: 100%;
}

.jumbotron .heading-wrapper {
  padding-top: 22vh;
}

.jumbotron .heading h1 {
  font-size: 3.5rem;
  font-weight: 330;
  line-height: 1.2;
}

.jumbotron .heading p {
  font-weight: 300;
}

.jumbotron {
  min-height: 300px;
  height: 100vh;
  background: url(/img/louis-hansel-shotsoflouis-4FY6xU05qaQ-unsplash.jpg)
    no-repeat center center;
  background-size: cover;
  border-radius: 0%;
  margin: 0;
  padding: 0;
}

@media (min-width: 768px) {
  .jumbotron .heading h1 {
    font-size: 5.4rem;
  }

  .jumbotron .heading p {
    font-size: 1.25rem;
  }
}

.access__area a {
  color: #37383bb5 !important;
}

h1 {
  font-family: "Truculenta", sans-serif;
}
</style>
