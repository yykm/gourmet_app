<template>
  <b-jumbotron fluid container-fluid>
    <div class="heading-wrapper">
      <div class="heading text-center">
        <h1>{{ site_name }}</h1>
        <p>
          <slot>お近くの飲食店をフリーワードで検索</slot>
        </p>
      </div>
    </div>
    <div v-if="!isLogin" class="access__area text-center">
      <b-link to="/login">ログイン</b-link>
      <span>｜</span>
      <b-link to="/register">新規登録</b-link>
    </div>
    <div v-else class="access__area text-center">
      <b-link to="/reserve">予約確認</b-link>
      <span>｜</span>
      <b-link to="/favorite">お気に入り</b-link>
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
    Search
  },
  computed: {
    ...mapGetters('App',['getApiStatus']),

    // 認証状態
    isLogin() {
      return this.$store.getters["App/isLogin"];
    }
  },
  methods: {
    // ログアウト処理
    async logout() {
      await this.$store.dispatch("App/logout");

      if (this.getApiStatus) {
        this.$router.push("/login");
      }
    }
  },
  props: {
    site_name: {
      default: "Gourmet",
      type: String
    }
  }
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

/* タイトル */
.jumbotron .heading h1 {
  font-size: 3.5rem;
  font-weight: 330;
  line-height: 1.2;
}
/* リード文 */
.jumbotron .heading p {
  font-weight: 300;
}
/* 背景画像 */
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
</style>
