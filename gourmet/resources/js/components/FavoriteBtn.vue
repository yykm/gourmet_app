<template>
  <div class="favorite__wrapper">
    <!-- お気に入りボタン -->
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
        /></svg
      ><span>{{ favorite.likes_count }}</span>
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
      favorite: false
    };
  },
  props: {
    // 店舗ID
    id: {
      type: String,
      required: true
    }
  },
  computed: {
    ...mapGetters("App", ["isLogin"])
  },
  methods: {
    ...mapMutations("Message", ["setContent"]),

    // 1店舗に関するお気に入り情報取得
    async fetchFavorite() {
      const response = await axios.get(`/api/favorites/${this.id}`);

      if (response.status !== ERR.OK) {
        this.$store.commit("Err/setCode", response.status);
        return;
      }

      this.favorite = response.data;
    },

    // いいねボタン押下のハンドラ
    onClickLike() {
      if (!this.isLogin) {
        this.setContent({
          success: false,
          content: "お気に入り機能を使うにはログインしてください",
          timeout: 3000
        });
        return;
      }

      if (this.favorite.liked_by_user) {
        this.unlike();
      } else {
        this.like();
      }
    },

    // いいね
    async like() {
      const response = await axios.put(`/api/favorites/${this.id}`);

      if (response.status !== ERR.OK) {
        this.$store.commit("Err/setCode", response.status);
        return;
      }

      this.setContent({
        success: true,
        content: "お気に入りに登録しました",
        timeout: 3000
      });
      this.favorite.likes_count = this.favorite.likes_count + 1;
      this.favorite.liked_by_user = true;
    },

    // いいね解除
    async unlike() {
      const response = await axios.delete(`/api/favorites/${this.id}`);

      if (response.status !== ERR.OK) {
        this.$store.commit("Err/setCode", response.status);
        return;
      }

      this.setContent({
        success: true,
        content: "お気に入りから解除しました",
        timeout: 3000
      });
      this.favorite.likes_count = this.favorite.likes_count - 1;
      this.favorite.liked_by_user = false;
    }
  },

  async created() {
    await this.fetchFavorite();
  }
};
</script>

<style scoped>
.favorite__wrapper {
  position: relative;
}

.favorite__btn {
  position: absolute;
  top: 1.5em;
  right: 1.5em;
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
