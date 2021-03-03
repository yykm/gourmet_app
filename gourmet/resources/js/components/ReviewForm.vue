<template>
  <!-- 口コミ投稿フォーム -->
  <div class="review__form">
    <!-- 口コミ投稿フォームタイトル -->
    <div  v-if="login" class="review__title text-left">
      <p>口コミを投稿する</p>
    </div>
    <div class="review__controls">
      <b-form @submit.prevent="addComment">
        <div v-if="isLogin" class="inpute__area">
          <!-- 口コミ内容 -->
          <b-form-textarea
            v-model="comment"
            rows="6"
            max-rows="7"
          ></b-form-textarea>

          <!-- 写真ファイル -->
          <b-form-file
            v-model="photo"
            class="mt-3"
            browse-text="画像を選択"
            placeholder="jpg jpeg png gif 形式"
          ></b-form-file>

          <!-- エラーメッセージ表示領域 -->
          <div v-if="errors" class="errors">
            <p class="text-danger">{{ errors }}</p>
          </div>
        </div>

        <!-- サブミット -->
        <b-button
          type="submit"
          variant="primary"
          class="px-4 py-2 my-5 mx-auto d-block"
          >口コミを投稿する</b-button
        >
      </b-form>
    </div>
  </div>
</template>

<script>
import { mapMutations, mapGetters } from "vuex";
import { STATUS } from "./../util.js";

export default {
  name: "ReviewForm",
  data() {
    return {
      photo: null, // 写真ファイル
      comment: "", // 口コミ内容
      errors: null, // エラーメッセージ
    };
  },
  props: {
    // 店舗情報
    shop: {
      type: Object,
      required: true,
    },
  },
  computed: {
    ...mapGetters("App", ["isLogin", "getShop"]),
  },
  methods: {
    ...mapMutations("Err", ["setCode"]),
    ...mapMutations("Message", ["setContent"]),

    // コメント投稿
    async addComment() {
      // ログイン状態ではない旨をメッセージ表示
      if (!this.isLogin) {
        this.setContent({
          success: false,
          content: "口コミ投稿機能を使うにはログインしてください",
          timeout: 1500,
        });
        return;
      }

      // 口コミ内容未入力の場合通知して処理中断
      if (!this.comment) {
        // 失敗メッセージ表示
        this.setContent({
          success: false,
          content: "口コミを入力してください",
          timeout: 1500,
        });
        return;
      }

      // multipart/form-data形式で送信
      const formData = new FormData();

      // 店舗情報をパラメータに追加
      formData.append("shop", JSON.stringify(this.shop));

      try {
        // 写真が入力されている場合
        if (this.photo) {
          // 写真ファイルオブジェクトをパラメータに追加
          formData.append("photo", this.photo);

          // 写真投稿処理呼び出し
          const response = await this.postPhoto(formData);

          // photoパラメータを削除
          formData.delete("photo");
          // 投稿した写真のidをパタメータに追加
          formData.append("photo_id", response.data.id);
        }

        // 口コミ内容をパラメータに追加
        formData.append("content", this.comment);

        // 口コミ投稿処理呼び出し
        await this.postComment(formData);

        // エラーメッセージ及び入力値初期化
        this.photo = null;
        this.comment = "";
        this.errors = null;

        // 写真投稿イベントを発火
        this.$emit("reviewPost");

        // 成功メッセージ表示
        this.setContent({
          success: true,
          content: "レビューが投稿されました！",
          timeout: 3000,
        });
      } catch (err) {
        // エラーメッセージを格納
        this.errors = err;

        // 失敗メッセージ表示
        this.setContent({
          success: false,
          content: "コメントの投稿に失敗しました",
          timeout: 3000,
        });
      }
    },

    // 写真投稿処理
    async postPhoto(formData) {
      // 写真投稿API呼び出し
      const response = await axios
        .post(`/api/photos`, formData)
        .catch((err) => err.response || err);

      // バリデーションエラー
      if (response.status === STATUS.UNPROCESSABLE_ENTITY) {
        throw new Error("バリデーション_写真ファイル");
      }
      // その他エラー
      else if (response.status !== STATUS.CREATED) {
        // エラーコード設定
        this.setCode(response.status);
        throw new Error(response.data.message);
      }

      return response;
    },

    // 口コミ投稿処理
    async postComment(formData) {
      // 口コミ投稿API呼び出し
      const response = await axios
        .post(`/api/comments`, formData)
        .catch((err) => err.response || err);

      // バリデーションエラー
      if (response.status === STATUS.UNPROCESSABLE_ENTITY) {
        throw new Error("バリデーション_口コミ");
      }

      if (response.status !== STATUS.CREATED) {
        // エラーコード設定
        this.setCode(response.status);
        throw new Error(response.data.message);
      }

      return response;
    },
  },
};
</script>

<style scoped>
.review__title {
  font-size: 1.15rem;
}

.review__form {
  max-width: 700px;
  width: 65vw;
  margin-left: auto;
  margin-right: auto;
}

@media (max-width: 767.98px) {
  .review__form {
    width: 90%;
  }
}

form .custom-file {
  margin-top: 1.2rem;
}
</style>
