<template>
  <div class="header">
    <b-navbar toggleable="lg" type="light" variant="light" class="shadow-sm">
      <b-navbar-brand to="/">Gourmet</b-navbar-brand>

      <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

      <b-collapse id="nav-collapse" is-nav>
        <!-- ログイン / ログアウト表示領域 -->
        <b-navbar-nav class="ml-auto">
          <b-nav-item v-if="!isLogin" to="/login">ログイン</b-nav-item>

          <b-nav-item-dropdown v-else right>
            <!-- Using 'button-content' slot -->
            <template #button-content>
              <em>{{ userName }}</em>
            </template>
            <b-dropdown-item to="#">プロフィール</b-dropdown-item>
            <b-dropdown-item @click.prevent="onClick">ログアウト</b-dropdown-item>
          </b-nav-item-dropdown>
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
  props: {
    site_name: {
      type: String,
      default: "site_name"
    }
  },
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
</style>
