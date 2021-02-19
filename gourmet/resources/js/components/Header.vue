<template>
  <!-- 共通ヘッダー -->
  <div id="header">
    <b-navbar
      toggleable="lg"
      type="light"
      variant="light"
      class="nav__wrapper shadow-sm"
    >
      <b-navbar-brand to="/" class="site_title">{{ app_name }}</b-navbar-brand>

      <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>
      <b-collapse id="nav-collapse" is-nav>
        <!--  ログイン状態 -->
        <b-navbar-nav v-if="isLogin" class="ml-auto">
          <b-nav-item-dropdown right class="mx-0">
            <template #button-content>
              <em class="mr-1">{{ userName }}</em>
            </template>
            <b-dropdown-item to="/mypage">マイページ</b-dropdown-item>
            <b-dropdown-item @click.prevent="onClick"
              >ログアウト</b-dropdown-item
            >
          </b-nav-item-dropdown>
        </b-navbar-nav>

        <!-- 未ログイン状態 -->
        <b-navbar-nav v-else class="ml-auto">
          <b-nav-item to="/login">ログイン</b-nav-item>
          <b-nav-item to="/register">新規登録</b-nav-item>
        </b-navbar-nav>
      </b-collapse>
    </b-navbar>
  </div>
</template>

<script>
import { APP } from "./../store/const.js";
import { mapGetters, mapActions } from "vuex";

export default {
  name: "Header",
  computed: {
    ...mapGetters({
      isLogin: APP.getAppURI(APP.IS_LOGIN),
      userName: APP.getAppURI(APP.USER_NAME),
      apiStatus: APP.getAppURI(APP.GET_API_STATUS),
    }),
  },
  data() {
    return {
      app_name: process.env.MIX_APP_NAME, // アプリ名
    };
  },
  methods: {
    ...mapActions({
      logout: APP.getAppURI(APP.LOGOUT),
    }),

    // ログアウト
    async onClick() {
      // ログアウトAPI呼び出し
      await this["logout"]();

      // API通信成功時
      if (this["apiStatus"]) {
        // トップページに移動する
        this.$router.push("/");
      }
    },
  },
};
</script>

<style scoped>
.bg-light {
  background-color: #fff !important;
}

.nav__wrapper {
  position: relative;
}

.site_title {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  -webkit-transform: translate(-50%, -50%);
  -ms-transform: translate(-50%, -50%);
  color: rgb(0 0 0 / 67%) !important;
  font-size: 2rem;
  font-family: "Truculenta", sans-serif;
}

li.nav-item {
  margin-right: 0.8rem;
}
</style>
