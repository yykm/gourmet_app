<template>
  <!-- 退会確認用モーダル -->
  <div class="withdrawal">
    <b-link @click.prevent="toggleModal">
      <b-list-group-item>退会する</b-list-group-item>
    </b-link>

    <b-modal ref="my-modal" hide-footer hide-header centered>
      <div class="content__wrapper">
        <div class="d-block text-center mb-5">
          <h3>本当に退会しますか？</h3>
        </div>
        <div class="confirm text-center">
          <b-button variant="primary" @click="withdrawal" class="mr-3"
            >退会する</b-button
          >
          <b-button variant="secondary" @click="toggleModal"
            >キャンセル</b-button
          >
        </div>
      </div>
    </b-modal>
  </div>
</template>

<script>
import { ERR } from "./../store/const.js";
import { mapMutations } from "vuex";

export default {
  name: "withdrawal",
  methods: {
    ...mapMutations("Message", ["setContent"]),
    ...mapMutations("App", ["setUser"]),
    ...mapMutations("Err", ["setCode"]),

    // モーダル閉じる
    toggleModal() {
      this.$refs["my-modal"].toggle("#toggle-btn");
    },
    // 退会処理
    async withdrawal() {
      // モーダル閉じる
      this.toggleModal();

      // 退会API(ユーザ情報削除)呼び出し
      const response = await axios
        .delete("/api/withdrawal")
        .catch((err) => err.response || err);

      // ステ－タスコード200以外エラー
      if (response.status !== ERR.OK) {
        this.setCode("setCode", response.status);

        // API通信失敗時（ユーザ削除処理失敗）
        this.setContent({
          success: false,
          content: "退会に失敗しました、管理者にお問い合わせください",
          timeout: 1500,
        });
        return;
      }

      // フロント側を未ログイン状態に
      this.setUser(null);

      // 退会完了ページへ
      this.$router.push("/thanks");
    },
  },
};
</script>

<style scoped>
.content__wrapper {
  padding-top: 2rem;
  padding-bottom: 1.5rem;
}
</style>
