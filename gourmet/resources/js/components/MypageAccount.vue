<template>
  <!-- マイページのサイドメニュー -->
  <div id="mypageAccount">
    <b-card no-body class="border-bottom-0">
      <!-- ヘッダー -->
      <template #header>
        <p class="mb-0">
          こんにちは、{{ user.name }}さん<br />ご本人ではない場合は<b-link
            @click.prevent="logout"
            >ログアウト</b-link
          >
        </p>
      </template>

      <!-- ボディー（未実装） -->
      <b-card-body>
        <b-card-title>ほげほげポイント（未実装）</b-card-title>
        <b-card-sub-title class="mb-2">合計残高 xxxポイント</b-card-sub-title>
        <b-card-text> ポイント交換する場合は... </b-card-text>
      </b-card-body>

      <!-- サービス利用履歴の表示リンク一覧 -->
      <b-list-group flush>
        <b-link @click.prevent="onClick('reservation')"
          ><b-list-group-item>予約履歴</b-list-group-item></b-link
        >
        <b-link @click.prevent="onClick('favorite')"
          ><b-list-group-item>お気に入りのお店</b-list-group-item></b-link
        >
        <b-link @click.prevent="onClick('photo')"
          ><b-list-group-item>投稿した写真</b-list-group-item></b-link
        >
        <b-link @click.prevent="onClick('comment')"
          ><b-list-group-item>投稿した口コミ</b-list-group-item></b-link
        >
        <Withdrawal />
      </b-list-group>

      <!-- 参考リンク -->
      <b-card-body>
        <p class="referrences">
          ** Webサービス制作にあたって参考にさせて頂いたサイト **
        </p>
        <a
          href="https://www.gnavi.co.jp/"
          class="card-link mr-3"
          target="”_blank”"
          >ぐるなび</a
        >
        <a href="http://webservice.recruit.co.jp/"
          ><img
            src="http://webservice.recruit.co.jp/banner/hotpepper-m.gif"
            alt="ホットペッパー Webサービス"
            width="88"
            height="35"
            border="0"
            title="ホットペッパー Webサービス"
        /></a>
      </b-card-body>
    </b-card>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import Withdrawal from "./../components/Withdrawal.vue";

export default {
  name: "MypageAccount",
  components: {
    Withdrawal,
  },
  props: {
    // ユーザ情報
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

    // サービス利用履歴の表示リンクが押下されたタイミングで
    // 親コンポーネントへ表示コンポーネント切り替えフラグを付与してイベント発火
    onClick(flag) {
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

/deep/ .list-group-flush > .list-group-item:last-child {
  border-width: 0 0 1px;
}

/deep/ .list-group a {
  color: #404e5fcc;
  text-decoration: none;
}
/deep/ .list-group-item:hover {
  background-color: #4755611a;
}

/deep/ .list-group-item {
  border: none;
  border-bottom-color: rgba(0, 0, 0, 0.125);
  border-bottom-style: solid;
  border-bottom-width: 1px;
}
</style>
