<template>
  <div id="login">
    <!-- ヘッダー -->
    <Header />

    <main class="py-4">
      <b-container>
        <b-card class="mx-auto" tag="main">
          <!-- ログイン/新規登録切り替えタブ -->
          <template #header>
            <b-nav tabs>
              <b-nav-item active>ログイン</b-nav-item>
              <b-nav-item to="register">新規登録</b-nav-item>
            </b-nav>
          </template>

          <ValidationObserver ref="observer" v-slot="{ handleSubmit }">
            <b-form
              @submit.prevent="handleSubmit(onSubmit)"
              @reset.prevent="onReset"
            >
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

              <!-- パスワード -->
              <!-- バリデーション：「必須」かつ「英数字」かつ「8文字以上20文字以下」 -->
              <validation-provider
                :rules="{ required: true, alpha_num: true, min: 8, max: 20 }"
                v-slot="validationContext"
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

              <!-- パスワードが間違っているなどエラーメッセージ表示部 -->
              <div v-if="loginErrors" class="errors text-danger text-center">
                {{ loginErrors }}
              </div>

              <!-- サブミット・入力リセットボタン -->
              <div class="text-center mb-2 mt-4 mt-md-5">
                <b-button
                  type="submit"
                  variant="primary"
                  class="px-3 px-md-4 mr-4"
                  >ログイン</b-button
                >
                <b-button type="reset" variant="danger" class="px-3 px-md-4"
                  >リセット</b-button
                >
              </div>

              <!-- パスワードリセット画面へのリンク（未実装） -->
              <!-- <div class="text-center mt-3">
                <b-link to="reset">パスワードをお忘れですか？</b-link>
              </div> -->
            </b-form>
          </ValidationObserver>
        </b-card>
      </b-container>
    </main>
  </div>
</template>

<script>
import { APP, ERR } from "./../store/const.js";
import { mapActions, mapGetters } from "vuex";
import { ValidationObserver, ValidationProvider } from "vee-validate";
import Header from "./../components/Header.vue";

export default {
  name: "Login",
  components: {
    ValidationObserver,
    ValidationProvider,
    Header,
  },
  data() {
    return {
      form: {
        email: "", // Eメール
        password: "", // パスワード
      },
    };
  },
  computed: {
    ...mapGetters({
      apiStatus: APP.getAppURI(APP.GET_API_STATUS),
      loginErrors: ERR.getErrURI(ERR.GET_LOGIN_ERROR_MESSAGE),
    }),
  },
  methods: {
    ...mapActions({
      login: APP.getAppURI(APP.LOGIN),
      setLoginErrorMessage: ERR.getErrURI(ERR.SET_LOGIN_ERROR_MESSAGE),
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

    // ログイン処理
    async onSubmit() {
      // ログインAPI呼び出し
      await this["login"](this.form);

      // API通信が成功した場合にトップページへ遷移
      if (this["apiStatus"]) {
        this.$router.push("/");
      }
    },
    // フォーム値の初期化
    onReset() {
      this.form.email = "";
      this.form.password = "";
      this["setLoginErrorMessage"](null);
      this.$nextTick(() => {
        this.$refs.observer.reset();
      });
    },
  },
  // タブ切り替え時にエラーメッセージを初期化
  created() {
    this["setLoginErrorMessage"](null);
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
