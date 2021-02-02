<template>
  <div class="header">
    <b-navbar toggleable="lg" type="light" variant="light" class="nav__wrapper shadow-sm">
      <b-navbar-brand to="/" class="site_title">Gourmet</b-navbar-brand>

      <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>
      <b-collapse id="nav-collapse" is-nav>
        <!--  ログイン時 -->
        <b-navbar-nav v-if="isLogin" class="ml-auto">
          <b-nav-item to="/reserve">予約確認</b-nav-item>
          <b-nav-item to="/favorite">お気に入り</b-nav-item>

          <b-nav-item-dropdown right class="mx-0">
            <!-- Using 'button-content' slot -->
            <template #button-content>
              <em class="mr-1">{{ userName }}</em>
            </template>
            <b-dropdown-item @click.prevent="onClick"
              >ログアウト</b-dropdown-item
            >
          </b-nav-item-dropdown>
        </b-navbar-nav>
        <!-- ログアウト時 -->
        <b-navbar-nav v-else class="ml-auto">
          <b-nav-item to="/login">ログイン</b-nav-item>
          <b-nav-item to="/register">新規登録</b-nav-item>
        </b-navbar-nav>
      </b-collapse>
    </b-navbar>
  </div>
</template>

<script>
import { APP, ERR } from "./../store/const.js";
import { mapGetters, mapActions } from "vuex";

export default {
  name: "Header",
  computed: {
    ...mapGetters({
      isLogin: APP.getAppURI(APP.IS_LOGIN),
      userName: APP.getAppURI(APP.USER_NAME),
      apiStatus: APP.getAppURI(APP.GET_API_STATUS)
    })
  },
  methods: {
    ...mapActions({
      logout: APP.getAppURI(APP.LOGOUT)
    }),

    // ログアウト
    async onClick() {
      // ストアのlogoutアクションを呼び出す
      await this["logout"]();

      // API通信成功時
      if (this["apiStatus"]) {
        // トップページに移動する
        this.$router.push("/");
      }
    }
  }
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
  left: 50%;
  right: 50%;
  font-size: 1.5rem;
}

li.nav-item {
  margin-right: 0.8rem;
}
</style>
