<template>
  <!-- 予約フォーム -->
  <div id="reserve">
    <div class="reserve__btn-area">
      <b-button variant="warning" @click="showDateModal" class="reserve__btn"
        >予約する</b-button
      >
    </div>

    <!-- 日付・人数入力モーダル -->
    <b-modal
      :ref="'date-modal' + shop.id"
      centered
      hide-header
      hide-footer
      title="ネット予約"
    >
      <div class="d-block text-center">
        <h4>ネット予約</h4>
      </div>
      <b-container class="py-2">
        <b-form @submit.prevent="showInfoModal">
          <!-- 人数  -->
          <b-row class="my-2 justify-content-center">
            <b-col cols="4" class="text-center reserve__items">人数</b-col>
            <b-col cols="12" md="7"
              ><b-form-select
                v-model="form.number"
                plain
                :options="options"
                required
              ></b-form-select
            ></b-col>
          </b-row>

          <!-- 来店日 -->
          <b-row class="my-2 justify-content-center">
            <b-col cols="4" class="text-center reserve__items">来店日</b-col>
            <b-col cols="12" md="7"
              ><b-form-datepicker
                v-model="form.date"
                label-no-date-selected="来店日"
                :date-format-options="{
                  year: 'numeric',
                  month: 'short',
                  day: '2-digit',
                  weekday: 'short',
                }"
                :min="new Date()"
                locale="ja"
                required
              ></b-form-datepicker
            ></b-col>
          </b-row>

          <!-- 時間 -->
          <b-row class="my-2 justify-content-center">
            <b-col cols="4" class="text-center reserve__items border-0"
              >時間</b-col
            >
            <b-col cols="12" md="7">
              <b-form-timepicker
                v-model="form.time"
                locale="en"
                minutes-step="15"
                menu-class="w-100 text-center"
                label-no-time-selected="来店時間"
                required
              ></b-form-timepicker>
            </b-col>
          </b-row>
          <b-button
            class="mt-3 w-75 mx-auto"
            type="submit"
            block
            variant="warning"
            >上記で予約を進める</b-button
          >
        </b-form>
      </b-container>
    </b-modal>

    <!-- 予約詳細・個人情報入力モーダル -->
    <b-modal
      :ref="'info-modal' + this.shop.id"
      centered
      size="lg"
      hide-footer
      hide-header
    >
      <!-- 前画面で設定した日時・人数・予約対象の店舗名をタイトルに表示 -->
      <div class="reserve__info-header text-center mb-4">
        <h4>
          {{ shop.name }}
        </h4>
        <p>
          {{ form.date }} {{ form.time | formatted_time }} /
          {{ form.number + "名" }}
        </p>
      </div>
      <b-container>
        <b-form @submit.prevent="onSubmit" @reset.prevent="hideInfoModal">
          <!-- 氏名 -->
          <b-row class="justify-content-center">
            <b-col cols="12" md="5" lg="3" class="text-center text-md-right">
              <label for="input-2" class="items">予約者名：</label>
            </b-col>
            <b-col cols="10" md="7">
              <b-form-group id="input-group-2">
                <b-form-input
                  id="input-2"
                  v-model="form.name"
                  type="text"
                  placeholder="山田太郎"
                  required
                ></b-form-input>
              </b-form-group>
            </b-col>
          </b-row>

          <!-- ふりがな -->
          <b-row class="justify-content-center">
            <b-col cols="12" md="5" lg="3" class="text-center text-md-right">
              <label for="input-3" class="items">ふりがな：</label>
            </b-col>
            <b-col cols="10" md="7">
              <b-form-group id="input-group-3">
                <b-form-input
                  id="input-3"
                  v-model="form.kana"
                  type="text"
                  placeholder="やまだたろう"
                  required
                ></b-form-input>
              </b-form-group>
            </b-col>
          </b-row>

          <!-- Eメール -->
          <b-row class="justify-content-center">
            <b-col cols="12" md="5" lg="3" class="text-center text-md-right">
              <label for="input-1" class="items">メールアドレス：</label>
            </b-col>
            <b-col cols="10" md="7">
              <b-form-group id="input-group-1">
                <b-form-input
                  id="input-1"
                  v-model="form.email"
                  type="email"
                  placeholder="hoge@example.com"
                  required
                ></b-form-input>
              </b-form-group>
            </b-col>
          </b-row>

          <!-- 電話番号 -->
          <b-row class="justify-content-center">
            <b-col cols="12" md="5" lg="3" class="text-center text-md-right">
              <label for="input-4" class="items">電話番号：</label>
            </b-col>
            <b-col cols="10" md="7">
              <b-form-group id="input-group-4">
                <b-form-input
                  id="input-4"
                  v-model="form.phone_num"
                  type="tel"
                  placeholder="例 : 09012345678（ハイフンなし） "
                  required
                ></b-form-input>
              </b-form-group>
            </b-col>
          </b-row>

          <!-- 利用目的 -->
          <b-row class="justify-content-center">
            <b-col cols="12" md="5" lg="3" class="text-center text-md-right">
              <label for="input-5" class="items">利用目的：</label>
            </b-col>
            <b-col cols="10" md="7">
              <b-form-group id="input-group-5">
                <b-form-select
                  id="input-5"
                  v-model="form.purpose"
                  :options="purposes"
                  placeholder="お選びください"
                ></b-form-select>
              </b-form-group>
            </b-col>
          </b-row>

          <!-- 要望・相談 -->
          <b-row class="justify-content-center">
            <b-col cols="12" md="5" lg="3" class="text-center text-md-right">
              <label for="input-6" class="items">要望・相談：</label>
            </b-col>
            <b-col cols="10" md="7">
              <b-form-group id="input-group-6">
                <b-form-textarea
                  id="input-6"
                  v-model="form.request"
                  placeholder="ご意見・ご要望など "
                ></b-form-textarea>
              </b-form-group>
            </b-col>
          </b-row>

          <!-- バリデーションエラー表示領域 -->
          <div v-if="errors" class="error text-danger">
            <ul v-for="(field, index) in errors" :key="index">
              {{
                index
              }}
              <li v-for="(error, index) in field" :key="index">
                {{ error }}
              </li>
            </ul>
          </div>

          <!-- 予約確定 / キャンセル -->
          <b-button
            type="submit"
            class="mt-3 w-50 mx-auto"
            block
            color="white"
            variant="danger"
            >予約を確定する</b-button
          >
          <b-button
            type="reset"
            class="mt-3 w-50 mx-auto"
            variant="outline-danger"
            block
            >キャンセルする</b-button
          >
        </b-form>
      </b-container>
    </b-modal>
  </div>
</template>

<script>
import { STATUS } from "./../util.js";
import { mapMutations,mapGetters } from "vuex";

export default {
  name: "Reserve",
  data() {
    return {
      form: {
        date: null, // 来店予定日
        number: null, // 人数
        time: null, // 来店予定日時
        email: null, // Eメールアドレス
        name: null, // 名前
        kana: null, // かな
        phone_num: null, // 電話番号
        purpose: null, // 利用目的
        request: null, // 要望・相談
      },
      // 利用目的選択オプション
      purposes: [
        { value: null, text: "お選びください", disabled: true },
        { value: "1", text: "誕生日" },
        { value: "2", text: "デート" },
        { value: "3", text: "接待" },
        { value: "4", text: "歓迎会" },
        { value: "5", text: "送別会" },
        { value: "6", text: "同窓会" },
        { value: "7", text: "忘年会" },
        { value: "8", text: "結婚式2次会" },
        { value: "9", text: "法事" },
        { value: "10", text: "食事" },
        { value: "11", text: "飲み会" },
        { value: "12", text: "合コン" },
        { value: "99", text: "その他" },
      ],
      errors: null, // エラーメッセージ
    };
  },
  computed: {
    ...mapGetters("App", ["isLogin", "getShop"]),
    
    // 人数選択項目のオプション(1~99人)
    options() {
      let numbers = [];
      numbers.push({
        value: null,
        text: "人数をお選びください",
        disabled: true,
      });

      for (let i = 1; i < 100; i++) {
        numbers.push({ value: i, text: i + "名" });
      }
      return numbers;
    },
  },
  methods: {
    ...mapMutations("Err", ["setCode"]),
    ...mapMutations("Message", ["setContent"]),

    // 日付・人数入力モーダルを開く
    showDateModal() {
      // ログイン済でなければ、通知して処理を終える
      if (!this.isLogin) {
        this.setContent({
          success: false,
          content: "予約機能を使うにはログインしてください",
          timeout: 1500,
        });
        return;
      }
      this.$refs["date-modal" + this.shop.id].show();
    },
    // 予約詳細・個人情報入力モーダルを開く
    showInfoModal() {
      this.$refs["info-modal" + this.shop.id].show();
      this.$refs["date-modal" + this.shop.id].hide();
    },
    // 日付・人数入力モーダルを閉じる
    hideDateModal() {
      this.$refs["date-modal" + this.shop.id].hide();
      this.onReset();
    },
    // 予約詳細・個人情報入力モーダルを閉じる
    hideInfoModal() {
      this.$refs["info-modal" + this.shop.id].hide();
      this.hideDateModal();
    },
    // 入力値リセット
    onReset() {
      this.form = {
        date: null,
        number: null,
        time: null,
        email: null,
        name: null,
        kana: null,
        phone_num: null,
        purpose: null,
        request: null,
      };
      this.errors = null;
    },
    // フォーム送信
    async onSubmit() {
      // 予約情報送信
      const response = await axios
        .post("/api/reserve", {
          form: this.form,
          shop: JSON.stringify(this.shop),
        })
        .catch((err) => err.response || err);

      // バリデーションエラー
      if (response.status === STATUS.UNPROCESSABLE_ENTITY) {
        this.errors = response.data.errors;
        return;
      }

      // モーダルと入力値リセット
      this.hideInfoModal();

      // その他エラー
      if (response.status !== STATUS.CREATED) {
        this.setCode(response.status);

        // 失敗メッセージ表示
        this.setContent({
          success: false,
          content: "予約に失敗しました",
          timeout: 3000,
        });
        return;
      }

      // 成功時は予約情報を予約完了ページに送信しページ遷移
      this.$router.replace({
        path: "/reserved",
        query: { reservation: response.data },
      });
    },
  },
  props: {
    // 店舗情報
    shop: {
      type: Object,
      required: true,
    },
  },
  filters: {
    // hh:mm形式へ変換
    formatted_time(time) {
      if (!time) {
        return null;
      }

      return time.slice(0, 5);
    },
  },
};
</script>

<style scoped>
.close {
  margin: 0;
}

.reserve__items {
  padding-top: 0.375rem;
  padding-bottom: 0.375rem;
  line-height: 1.5;
  vertical-align: middle;
}

.items {
  padding: 0.375rem 0.75rem;
}

.reserve__info-header p {
  color: #656fb5;
}
</style>
