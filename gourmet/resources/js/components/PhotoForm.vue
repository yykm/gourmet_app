<template>
  <div id="photoList">
    <div class="text-center my-3">
      <b-button id="toggle-btn" @click="toggleModal">写真を投稿する</b-button>
    </div>
    <b-modal
      ref="my-modal"
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

        <b-form-group description="jpg, jpeg, png, gif 形式">
          <!-- 写真投稿コントロール -->
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
        <input type="hidden" name="shop_Id" :value="shopId" />

        <!-- 投稿 / 入力内容リセットボタン -->
        <b-button type="submit" class="mt-2" variant="outline-danger" block
          >写真を投稿</b-button
        >
        <b-button type="reset" class="mt-2" variant="outline-warning" block
          >キャンセル</b-button
        >
      </b-form>
      <Loader v-else>Sending your photo...</Loader>
    </b-modal>
  </div>
</template>

<script>
import { mapMutations, mapActions } from "vuex";
import { ERR } from "./../store/const.js";
import Loader from "./../components/Loader.vue";

export default {
  name: "PhotoForm",
  components: {
    Loader
  },
  data() {
    return {
      file: null, // 入力されたファイル名
      preview: null, // プレビュー表示フラグ
      errors: null, // エラーメッセージ用
      loading: null // ローダー表示フラグ
    };
  },
  props: {
    shopId: {
      type: String,
      required: true
    }
  },
  computed: {
    // 選択された画像、さもなければ初期画像
    rePreview() {
      return this.preview ?? "/img/l_e_others_500.png";
    }
  },
  methods: {
    ...mapMutations("Err", ["setCode"]),
    ...mapMutations("Message", ["setContent"]),

    // ファイルの入力値を初期化
    reset() {
      this.file = null;
      this.preview = null;
    },
    // モーダルの表示有無切り替え
    toggleModal() {
      this.reset();
      this.errors = null;
      this.$refs["my-modal"].toggle("#toggle-btn");
    },
    // フォームでファイルが選択されたら実行される
    onFileChange(event) {
      // 何も選択されていなかったら処理中断
      if (!this.file) {
        this.reset();
        return;
      }

      // ファイルが画像ではなかったら処理中断
      if (!this.file.type.match("image.*")) {
        this.reset();
        return;
      }

      // FileReaderクラスのインスタンスを取得
      const reader = new FileReader();

      // ファイルを読み込み終わったタイミングで実行する処理
      reader.onload = e => {
        // previewに読み込み結果（データURL形式）を代入する
        this.preview = e.target.result;
      };

      // ファイルを読み込む
      reader.readAsDataURL(this.file);
    },
    // ファイルアップロード
    async onSubmit() {
      // ローダー表示
      this.loading = true;

      const formData = new FormData();

      // FormDataオブジェクトに写真ファイルと店舗ID追加
      formData.append("photo", this.file);
      formData.append("shop_id", this.shopId);
      // 送信
      const response = await axios
        .post("/api/photos", formData)
        .catch(err => err.response || err);

      // ローダ非表示
      this.loading = false;

      // バリデーションエラー
      if (response.status === ERR.UNPROCESSABLE_ENTITY) {
        this.errors = response.data.errors;
        return;
      }
      // 入力値リセットし閉じる
      this.toggleModal();

      // その他エラー
      if (response.status !== ERR.CREATED) {
        this.setCode(response.status);

        // 失敗メッセージ表示
        this.setContent({
          success: false,
          content: "写真の投稿に失敗しました",
          timeout: 3000
        });
        return;
      }

      // 成功メッセージ表示
      this.setContent({
        success: true,
        content: "写真が投稿されました！",
        timeout: 3000
      });
    }
  }
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
