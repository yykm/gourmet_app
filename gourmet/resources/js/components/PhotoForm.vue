<template>
  <!-- 写真投稿フォーム -->
  <div id="photoList">
    <!-- "写真を投稿する"ボタン -->
    <b-button id="toggle-btn" pill @click="toggleModal" class="px-3 py-2"
      ><svg
        xmlns="http://www.w3.org/2000/svg"
        width="20"
        height="20"
        fill="currentColor"
        class="bi bi-cloud-arrow-up-fill mr-2"
        viewBox="0 0 16 16"
      >
        <path
          d="M8 2a5.53 5.53 0 0 0-3.594 1.342c-.766.66-1.321 1.52-1.464 2.383C1.266 6.095 0 7.555 0 9.318 0 11.366 1.708 13 3.781 13h8.906C14.502 13 16 11.57 16 9.773c0-1.636-1.242-2.969-2.834-3.194C12.923 3.999 10.69 2 8 2zm2.354 5.146a.5.5 0 0 1-.708.708L8.5 6.707V10.5a.5.5 0 0 1-1 0V6.707L6.354 7.854a.5.5 0 1 1-.708-.708l2-2a.5.5 0 0 1 .708 0l2 2z"
        /></svg
      >写真を投稿する</b-button
    >

    <!-- 投稿フォームをモーダル表示 -->
    <b-modal
      ref="my-modal"
      :centered="true"
      body-class="text-center"
      hide-header-close
      header-class="justify-content-center"
      hide-footer
      title="写真を投稿する"
    >
      <!--  エラーメッセージ表示領域 -->
      <div class="errors text-danger text-center" v-if="errors && !loading">
        <ul v-if="errors">
          <li v-if="errors.shop_id">
            {{ "店舗IDが不正です。管理者にご連絡下さい" }}
          </li>
          <li v-if="errors.photo">
            {{ "アップロードされたファイル形式が不正、又は未入力です" }}
          </li>
        </ul>
      </div>

      <!-- 投稿フォーム本体 -->
      <b-form
        @submit.prevent="onSubmit"
        @reset.prevent="toggleModal"
        class="text-center"
        v-if="!loading"
      >
        <!-- プレビュー表示領域 -->
        <output>
          <b-img
            fluid
            block
            center
            :src="rePreview"
            alt="image"
            class="preview my-2"
          />
        </output>

        <!-- 写真投稿コントロール -->
        <b-form-group description="jpg, jpeg, png, gif 形式">
          <b-form-file
            v-model="file"
            ref="file-input"
            name="photo"
            @input="onFileChange"
            class="photo-input"
            id="post"
          ></b-form-file>
        </b-form-group>
        <label for="post" class="photo-btn btn btn-primary btn-block">
          アップロードする画像を選択
        </label>

        <!-- 店舗IDを送信するためhiddenフィールドを用意 -->
        <!-- <input type="hidden" name="shop" :value="shop" /> -->

        <!-- 投稿 / 入力内容リセットボタン -->
        <b-button type="submit" class="mt-2" variant="outline-danger" block
          >写真を投稿</b-button
        >
        <b-button type="reset" class="mt-2" variant="outline-warning" block
          >キャンセル</b-button
        >
      </b-form>

      <!-- ローダー -->
      <Loader v-else>Sending your photo...</Loader>
    </b-modal>
  </div>
</template>

<script>
import { mapMutations, mapGetters } from "vuex";
import { STATUS } from "./../util.js";
import Loader from "./../components/Loader.vue";

export default {
  name: "PhotoForm",
  components: {
    Loader,
  },
  data() {
    return {
      file: null, // 入力されたファイルオブジェクト
      preview: null, // プレビュー領域表示フラグ
      errors: null, // エラーメッセージ
      loading: null, // ローダー表示フラグ
      shop: null, // 店舗情報
    };
  },
  props: {
    // 店舗ID
    shop_id: {
      type: String,
      required: true,
    },
  },
  computed: {
    ...mapGetters("App", ["isLogin", "getShop"]),

    // プレビュー領域の表示画像URLを返却
    rePreview() {
      return this.preview ?? "/img/l_e_others_500.png";
    },
  },
  methods: {
    ...mapMutations("Err", ["setCode"]),
    ...mapMutations("Message", ["setContent"]),

    // 入力値を初期化
    reset() {
      this.file = null;
      this.preview = null;
    },
    // モーダルの表示有無切り替え
    toggleModal() {
      // ログイン状態ではない旨をメッセージ表示
      if (!this.isLogin) {
        this.setContent({
          success: false,
          content: "写真投稿機能を使うにはログインしてください",
          timeout: 1500,
        });
        return;
      }

      this.reset();
      this.errors = null;
      this.$refs["my-modal"].toggle("#toggle-btn");
    },
    // フォームでファイルが選択される度実行される
    onFileChange(event) {
      // 何も選択されていなかったら処理中断
      if (!this.file) {
        this.reset();
        return;
      }

      // ファイルが画像ではなかったら処理中断
      if (!this.file.type.match("image.*")) {
        // 画像ファイルではない旨をメッセージ表示
        this.setContent({
          success: false,
          content: "画像ファイルではありません",
          timeout: 1500,
        });

        this.reset();
        return;
      }

      // FileReaderクラスのインスタンスを取得
      const reader = new FileReader();

      // ファイルを読み込み終わったタイミングで実行する処理
      reader.onload = (e) => {
        // previewに読み込み結果（データURL形式）を代入する
        this.preview = e.target.result;
      };

      // ファイルを読み込む
      reader.readAsDataURL(this.file);
    },
    // ファイルアップロード
    async onSubmit() {
      this.loading = true;

      const formData = new FormData();

      // Content-Type=multipart/form-data形式で送信
      formData.append("photo", this.file);
      formData.append("shop", JSON.stringify(this.shop));
      // 写真投稿API呼び出し
      const response = await axios
        .post("/api/photos", formData)
        .catch((err) => err.response || err);

      this.loading = false;

      // バリデーションエラー
      if (response.status === STATUS.UNPROCESSABLE_ENTITY) {
        this.errors = response.data.errors;
        return;
      }
      // 入力値リセットし閉じる
      this.toggleModal();

      // その他エラー
      if (response.status !== STATUS.CREATED) {
        this.setCode(response.status);

        // 失敗メッセージ表示
        this.setContent({
          success: false,
          content: "写真の投稿に失敗しました",
          timeout: 3000,
        });
        return;
      }

      // 写真一覧画面を更新するために親コンポーネントに成功の際にイベント発火
      this.$emit("photoPost");

      // 成功メッセージ表示
      this.setContent({
        success: true,
        content: "写真が投稿されました！",
        timeout: 3000,
      });
    },
  },
  created() {
    // 店舗オブジェクトの取得
    this.shop = this.getShop(this.shop_id);
  },
};
</script>

<style scoped>
.preview {
  border-radius: 2px;
  max-height: 300px;
}

.photo-input {
  display: none;
}

.errors {
  list-style: none;
}
</style>
