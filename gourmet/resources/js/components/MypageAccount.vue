<template>
  <div id="mypageAccount">
    <b-card no-body class="border-bottom-0">
      <template #header>
        <p class="mb-0">
          こんにちは、{{ user.name }}さん<br />ご本人ではない場合は<b-link
            @click.prevent="logout"
            >ログアウト</b-link
          >
        </p>
      </template>

      <b-card-body>
        <b-card-title>ほげほげポイント（未実装）</b-card-title>
        <b-card-sub-title class="mb-2">合計残高 xxxポイント</b-card-sub-title>
        <b-card-text> ポイント交換する場合は... </b-card-text>
      </b-card-body>

      <b-list-group flush>
        <b-list-group-item>会員情報変更（未実装）</b-list-group-item>
        <b-list-group-item>友達を紹介する（未実装）</b-list-group-item>
        <b-list-group-item>退会する（未実装）</b-list-group-item>
        <b-list-group-item>未実装コンテンツ...</b-list-group-item>
      </b-list-group>

      <b-card-body>
        <p class="referrences">
          ** Webサービス制作にあたって参考にさせて頂いたサイト **
        </p>
        <a href="https://www.gnavi.co.jp/" class="card-link" target="”_blank”"
          >ぐるなび</a
        >
        <a
          href="https://webservice.recruit.co.jp/doc/hotpepper/reference.html"
          class="card-link"
          target="”_blank”"
          >使用API(グルメサーチAPI)</a
        >
      </b-card-body>

      <!-- <b-card-footer>This is a footer</b-card-footer> -->
    </b-card>
  </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  name: "MypageAccount",
  props: {
    user: {
      type: Object,
      required: false,
    },
  },
  computed: {
    ...mapGetters("App", ["getApiStatus"]),
  },
  methods: {
    // ログアウト処理
    async logout() {
      await this.$store.dispatch("App/logout");

      if (this.getApiStatus) {
        this.$router.push("/login");
      }
    },
  },
};
</script>

<style scoped>
.card-body,
.card-subtitle,
.list-group {
  color: #1625314a !important;
}

.referrences {
  color: black;
}

.list-group-flush > .list-group-item:last-child {
  border-width: 0 0 1px;
}
</style>
