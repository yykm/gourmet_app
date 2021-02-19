<template>
  <!-- お気に入りボタン -->
  <div id="favorite">
    <!-- 'btn__action--liked'スタイルクラスでいいね済か否かで表示を切り替える -->
    <button
      title="Like Shop"
      @click.prevent="onClickLike"
      class="favorite__btn shadow-sm py-1 px-3"
      :class="{ 'btn__action--liked': favorite.liked_by_user }"
    >
      <span>お気に入り</span>
      <svg
        xmlns="http://www.w3.org/2000/svg"
        width="17"
        height="17"
        fill="currentColor"
        class="bi bi-heart-fill mr-1"
        viewBox="0 0 16 16"
        :class="{ 'icon__action--liked': !favorite.liked_by_user }"
      >
        <path
          fill-rule="evenodd"
          d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"
        />
      </svg>
      <!-- お気に入り数 -->
      <span>{{ favorite.likes_count }}</span>
    </button>
  </div>
</template>
<script>
import { ERR } from "./../store/const.js";
import { mapMutations, mapGetters } from "vuex";

export default {
  name: "FavoriteBtn",
  data() {
    return {
      favorite: false, // お気に入り情報
    };
  },
  props: {
    // 店舗ID
    shop: {
      type: Object,
      required: true,
    },
  },
  computed: {
    ...mapGetters("App", ["isLogin", "getShop"]),
  },
  methods: {
    ...mapMutations("Message", ["setContent"]),

    // 1店舗に関するお気に入り情報取得
    async fetchFavorite() {
      const response = await axios.get(`/api/favorites/${this.shop.id}`);

      if (response.status !== ERR.OK) {
        this.$store.commit("Err/setCode", response.status);
        return;
      }

      // 紐づく店舗がない場合
      if (!response.data) {
        this.favorite = {
          liked_by_user: false,
          likes_count: 0,
        };
        return;
      }

      this.favorite = response.data;
    },

    // いいねボタン押下時のハンドラ
    onClickLike() {
      // ログイン済でなければ、通知して処理を終える
      if (!this.isLogin) {
        this.setContent({
          success: false,
          content: "お気に入り機能を使うにはログインしてください",
          timeout: 1500,
        });
        return;
      }

      // いいね済であればお気に入り解除処理
      // いいねしていなければ、お気に入り登録処理
      if (this.favorite.liked_by_user) {
        this.unlike();
      } else {
        this.like();
      }
    },

    // お気に入り登録
    async like() {
      
      // application/x-www-form-urlencoded形式で送信
      var params = new URLSearchParams();
      params.append("shop", JSON.stringify(this.shop));

      // お気に入り登録API
      const response = await axios
        .post("/api/favorites", params)
        .catch((err) => err.response || err);

      // お気に入り登録失敗
      if (response.status !== ERR.OK) {
        this.$store.commit("Err/setCode", response.status);
        return;
      }

      // お気に入り登録成功の通知
      this.setContent({
        success: true,
        content: "お気に入りに登録しました",
        timeout: 1500,
      });
      // お気に入り数を+1、お気に入り済に設定
      this.favorite.likes_count = this.favorite.likes_count + 1;
      this.favorite.liked_by_user = true;
    },

    // お気に入り解除
    async unlike() {
      // お気に入り解除API
      const response = await axios.delete(`/api/favorites/${this.shop.id}`);

      // お気に入り解除失敗
      if (response.status !== ERR.OK) {
        this.$store.commit("Err/setCode", response.status);
        return;
      }

      // お気に入り登録失敗の通知
      this.setContent({
        success: true,
        content: "お気に入りから解除しました",
        timeout: 1500,
      });
      
      // お気に入り数を-1、お気に入り未登録に設定
      this.favorite.likes_count = this.favorite.likes_count - 1;
      this.favorite.liked_by_user = false;
    },
  },

  created() {
    // お気に入りボタンの画面表示の際に、お気に入り情報を取得
    this.fetchFavorite();
  },
};
</script>

<style scoped>
.favorite__btn {
  outline: none;
  border-radius: 3px;
  transition: 0.3s;
  color: #f16d6d;
  background-color: #efefef54;
  border: 1px solid #acbbca33 !important;
}

.favorite__btn span {
  color: #050505;
}

.btn__action--liked {
  color: #fff !important;
  background-color: rgb(241, 109, 109);
  border: none !important;
}

.btn__action--liked span {
  color: #fff !important;
}
</style>
