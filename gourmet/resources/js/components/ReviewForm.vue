<template>
  <div class="review__form">
    <div class="review__title text-left">
      <p>口コミを投稿する</p>
    </div>
    <div class="review__controls">
      <b-form @submit.prevent="addComment">
        <!-- コメント -->
        <b-form-textarea
          v-model="comment"
          rows="6"
          max-rows="7"
        ></b-form-textarea>

        <!-- 写真データ -->
        <b-form-file
          v-model="photo"
          class="mt-3"
          browse-text="画像を選択"
          placeholder="jpg jpeg png gif 形式"
        ></b-form-file>

        <div v-if="errors" class="errors text-danger">
          <ul v-if="errors.content">
            <li v-for="msg in errors.content" :key="msg">{{ msg }}</li>
          </ul>
        </div>
        <b-button
          type="submit"
          variant="primary"
          class="px-4 py-2 my-5 mx-auto d-block"
          >口コミを投稿する</b-button
        >
      </b-form>
    </div>
    <div class="review__image"></div>
  </div>
</template>

<script>
import { mapMutations } from "vuex";
import { ERR } from "./../store/const.js";

export default {
  name: "ReviewForm",
  data() {
    return {
      photo: null,
      comment: "",
      errors: null,
    };
  },
  props: {
    shop: {
      type: Object,
      required: true,
    },
  },
  methods: {
    ...mapMutations("Err", ["setCode"]),
    ...mapMutations("Message", ["setContent"]),

    // コメント投稿
    async addComment() {
      const formData = new FormData();

      formData.append("shop", JSON.stringify(this.shop));
      // 写真が指定されている場合
      if (this.photo) {
        // 写真があれば写真投稿APIを呼び出す
        formData.append("photo", this.photo);

        const response = await axios
          .post(`/api/photos`, formData)
          .catch((err) => err.response || err);

        // バリデーションエラー
        if (response.status === ERR.UNPROCESSABLE_ENTITY) {
          // エラーメッセージを設定
          this.errors = response.data.errors;
          return;
        } else if (response.status !== ERR.CREATED) {
          // エラーコード設定
          this.setCode(response.status);

          // 失敗メッセージ表示
          this.setContent({
            success: false,
            content: "コメントの投稿に失敗しました",
            timeout: 3000,
          });
          return;
        }

        // photoフィールドを削除
        formData.delete("photo");
        // photo_idフィールドに投稿した写真のidを追加
        formData.append("photo_id", response.data.id);
      }

      // コメント内容を格納
      if (this.comment) {
        formData.append("content", this.comment);
      }

      const response = await axios
        .post(`/api/comments`, formData)
        .catch((err) => err.response || err);

      // バリデーションエラー
      if (response.status === ERR.UNPROCESSABLE_ENTITY) {
        // エラーメッセージを設定
        this.errors = response.data.errors;
        this.comment = "";
        return;
      }

      // 入力値初期化
      this.photo = null;
      this.comment = "";

      if (response.status !== ERR.CREATED) {
        // エラーコード設定
        this.setCode(response.status);

        // 失敗メッセージ表示
        this.setContent({
          success: false,
          content: "コメントの投稿に失敗しました",
          timeout: 3000,
        });
        return;
      }

      // エラーメッセージをクリア
      this.errors = null;

      // 写真投稿イベントを発火
      this.$emit("reviewPost");

      // 成功メッセージ表示
      this.setContent({
        success: true,
        content: "レビューが投稿されました！",
        timeout: 3000,
      });
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
