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
        <b-link @click.prevent="onClick('reservation')"
          ><b-list-group-item>予約履歴</b-list-group-item></b-link
        >
        <b-link @click.prevent="onClick('favorite')"
          ><b-list-group-item>お気に入り追加したお店</b-list-group-item></b-link
        >
        <b-link @click.prevent="onClick('photo')"
          ><b-list-group-item>投稿した写真</b-list-group-item></b-link
        >
        <b-link @click.prevent="onClick('comment')"
          ><b-list-group-item>投稿したレビュー</b-list-group-item></b-link
        >
        <b-link
          ><b-list-group-item>未実装コンテンツ..</b-list-group-item></b-link
        >
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
    onClick(flag) {
      // コンポーネント切り替えフラグ
      this.$emit("componentShow", flag);
    },
  },
};
</script>

<style scoped>
.card-body,
.card-subtitle {
  color: #1625314a !important;
}

.referrences {
  color: black;
}

.list-group-flush > .list-group-item:last-child {
  border-width: 0 0 1px;
}

.list-group > a {
  color: #404e5fcc;
  text-decoration: none;
}
.list-group-item:hover {
  background-color: #4755611a;
}

.list-group-item {
  border: none;
  border-bottom-color: rgba(0, 0, 0, 0.125);
  border-bottom-style: solid;
  border-bottom-width: 1px;
}
</style>
