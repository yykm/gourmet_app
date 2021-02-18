<template>
  <div id="register">
    <!-- ヘッダー -->
    <Header />

    <main class="py-4">
      <b-container>
        <b-card class="mx-auto" tag="main">
          <!-- ログイン/新規登録切り替えタブ -->
          <template #header>
            <b-nav tabs>
              <b-nav-item to="login">ログイン</b-nav-item>
              <b-nav-item active>新規登録</b-nav-item>
            </b-nav>
          </template>

          <ValidationObserver ref="observer" v-slot="{ handleSubmit }">
            <b-form
              @submit.prevent="handleSubmit(onSubmit)"
              @reset.prevent="onReset"
            >
              <!-- 名前 -->
              <!-- バリデーション：「必須」かつ「20文字以内」 -->
              <validation-provider
                :rules="{ required: true, max: 20 }"
                v-slot="validationContext"
                name="名前"
              >
                <b-form-group label="名前：" label-for="name">
                  <b-form-input
                    id="name"
                    type="text"
                    :state="getValidationState(validationContext)"
                    v-model="form.name"
                    placeholder="お名前（20文字以内）"
                  ></b-form-input>

                  <!-- バリデーション結果のメッセージ表示部 -->
                  <b-form-invalid-feedback>
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                  <b-form-valid-feedback
                    >有効な名前です。</b-form-valid-feedback
                  >
                </b-form-group>
              </validation-provider>

              <!-- メールアドレス -->
              <!-- バリデーション：「必須」かつ「Eメール形式」 -->
              <validation-provider
                :rules="{ required: true, email: true }"
                v-slot="validationContext"
                name="Eメールアドレス"
              >
                <b-form-group label="Eメールアドレス：" label-for="email">
                  <b-form-input
                    id="email"
                    v-model="form.email"
                    type="email"
                    :state="getValidationState(validationContext)"
                    placeholder="hoge@example.com"
                  ></b-form-input>

                  <!-- バリデーション結果のメッセージ表示部 -->
                  <b-form-invalid-feedback>
                    有効なメールアドレスではありません。
                  </b-form-invalid-feedback>
                  <b-form-valid-feedback
                    >有効なメールアドレスです。</b-form-valid-feedback
                  >
                </b-form-group>
              </validation-provider>

              <!-- パスワード  -->
              <!-- バリデーション：「必須」かつ「英数字」かつ「8文字以上20文字以内」 -->
              <validation-provider
                :rules="{ required: true, alpha_num: true, min: 8, max: 20 }"
                v-slot="validationContext"
                vid="form.password"
                name="パスワード"
              >
                <b-form-group label="パスワード：" label-for="password">
                  <b-form-input
                    id="password"
                    v-model="form.password"
                    type="password"
                    :state="getValidationState(validationContext)"
                    placeholder="パスワード（8から20文字の英数字）"
                  ></b-form-input>

                  <!-- バリデーション結果のメッセージ表示部 -->
                  <b-form-invalid-feedback>
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                  <b-form-valid-feedback
                    >有効なパスワードです。</b-form-valid-feedback
                  >
                </b-form-group>
              </validation-provider>

              <!-- パスワード確認用 -->
              <!-- バリデーション：「必須」かつ「パスワード項目と値が一致すること」」 -->
              <validation-provider
                :rules="{
                  required: true,
                  confirmed: 'form.password',
                }"
                name="パスワード（確認用）"
                v-slot="validationContext"
              >
                <b-form-group>
                  <b-form-input
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    :state="getValidationState(validationContext)"
                    placeholder="パスワード（確認用）"
                  ></b-form-input>

                  <!-- バリデーション結果のメッセージ表示部 -->
                  <b-form-invalid-feedback>
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                  <b-form-valid-feedback>一致しました</b-form-valid-feedback>
                </b-form-group>
              </validation-provider>

              <!-- 登録済などエラーメッセージ表示部 -->
              <div v-if="registerErrors" class="errors text-danger text-center">
                {{ registerErrors }}
              </div>

              <!-- サブミット・入力リセットボタン -->
              <div class="text-center mb-2 mt-4 mt-md-5 mb-md-4">
                <b-button
                  type="submit"
                  variant="primary"
                  class="px-3 px-md-4 mr-4"
                  >新規登録</b-button
                >
                <b-button type="reset" variant="danger" class="px-3 px-md-4"
                  >リセット</b-button
                >
              </div>
            </b-form>
          </ValidationObserver>
        </b-card>
      </b-container>
    </main>
  </div>
</template>

<script>
import Header from "./../components/Header.vue";
import { APP, ERR } from "./../store/const.js";
import { mapActions, mapGetters } from "vuex";
import { ValidationObserver, ValidationProvider } from "vee-validate";

export default {
  name: "Register",
  components: {
    ValidationObserver,
    ValidationProvider,
    Header,
  },
  data() {
    return {
      form: {
        name: "", // 名前
        email: "", // Eメール
        password: "", // パスワード
        password_confirmation: "", // パスワード確認用
      },
    };
  },
  computed: {
    ...mapGetters({
      apiStatus: APP.getAppURI(APP.GET_API_STATUS),
      registerErrors: ERR.getErrURI(ERR.GET_REGISTER_ERROR_MESSAGE),
    }),
  },
  methods: {
    ...mapActions({
      regist: APP.getAppURI(APP.REGISTER),
      setRegisterErrorMessage: ERR.getErrURI(ERR.SET_REGISTER_ERROR_MESSAGE),
    }),

    // バリデーション状態を返却
    /*
     dirty:フィールド値が一度でも操作により変更された
     validated:1度でもバリデートチェックされた
     valid:trueである場合、有効なフォールド値
     */
    getValidationState({ dirty, validated, valid = null }) {
      return dirty || validated ? valid : null;
    },

    // 新規登録処理
    async onSubmit() {
      // 新規登録API呼び出し
      await this["regist"](this.form);

      // API通信が成功した場合にトップページへ遷移
      if (this["apiStatus"]) {
        this.$router.push("/");
      }
    },
    // フォーム値の初期化
    onReset() {
      // Reset our form values
      this.form.name = "";
      this.form.email = "";
      this.form.password = "";
      this.form.password_confirmation = "";
      this["setRegisterErrorMessage"](null);
      this.$nextTick(() => {
        this.$refs.observer.reset();
      });
    },
  },
  // タブ切り替え時にエラーメッセージを初期化
  created() {
    this["setRegisterErrorMessage"](null);
  },
};
</script>

<style scoped>
.form-group {
  color: #7391be;
  font-weight: bold;
}
.nav-link {
  background-color: transparent !important;
  border: 0 !important;
  color: #b6c1cb;
}
.nav-tabs {
  border-bottom: 0;
}
.card-header {
  padding: 0.5rem;
}

@media (max-width: 767.98px) {
  .card {
    width: 100%;
  }
}
@media (min-width: 768px) {
  .card {
    width: 550px;
  }
}
</style>
