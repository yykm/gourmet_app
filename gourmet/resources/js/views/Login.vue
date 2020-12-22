<template>
  <div class="login">
    <Header :site_name="'Gourmet'"></Header>

    <main class="py-4">
      <b-container>
        <b-card class="mx-auto" tag="main">
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
              <validation-provider
                :rules="{ required: true, email: true }"
                v-slot="validationContext"
              >
                <b-form-group label="Eメールアドレス：" label-for="email">
                  <b-form-input
                    id="email"
                    v-model="form.email"
                    type="email"
                    :state="getValidationState(validationContext)"
                    placeholder="hoge@example.com"
                  ></b-form-input>
                  <b-form-invalid-feedback>
                    無効なメールアドレスです。
                  </b-form-invalid-feedback>
                  <b-form-valid-feedback
                    >有効なメールアドレスです。</b-form-valid-feedback
                  >
                </b-form-group>
              </validation-provider>

              <validation-provider
                :rules="{ required: true, alpha_num: true, min: 8,max: 20}"
                v-slot="validationContext"
              >
                <b-form-group label="パスワード：" label-for="password">
                  <b-form-input
                    id="password"
                    v-model="form.password"
                    type="password"
                    :state="getValidationState(validationContext)"
                    placeholder="パスワード（8から20文字の英数字）"
                  ></b-form-input>
                   <b-form-invalid-feedback>
                    無効なパスワードです。英数字8～20文字でご入力下さい。
                  </b-form-invalid-feedback>
                  <b-form-valid-feedback
                    >有効なパスワードです。</b-form-valid-feedback
                  >
                </b-form-group>
              </validation-provider>

              <div v-if="loginErrors" class="errors text-danger text-center">
                {{ loginErrors }}
              </div>

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
              <div class="text-center mt-3">
                <b-link to="reset">パスワードをお忘れですか？</b-link>
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

export default {
  name: "Login",
  components: {
    Header,
  },
  data() {
    return {
      form: {
        email: "",
        password: "",
      },
    };
  },
  computed: {
    ...mapGetters({
      apiStatus: APP.getAppURI(APP.GET_API_STATUS),
      loginErrors: ERR.getErrURI(ERR.GET_ERROR_MESSAGE),
    }),
  },
  methods: {
    ...mapActions({
      login: APP.getAppURI(APP.LOGIN),
      setErrorMessage: ERR.getErrURI(ERR.SET_ERROR_MESSAGE),
    }),

    getValidationState({ dirty, validated, valid = null }) {
      return dirty || validated ? valid : null;
    },

    async onSubmit() {
      // ストアのloginアクションを呼び出す
      await this["login"](this.form);

      // API通信成功時
      if (this["apiStatus"]) {
        // トップページに移動する
        this.$router.push("/");
      }
    },
    onReset() {
      // Reset our form values
      this.form.email = "";
      this.form.password = "";
      // Trick to reset/clear native browser form validation state
      this["setErrorMessage"](null);
      this.$nextTick(() => {
        this.$refs.observer.reset();
      });
    },
  },
  created() {
    this["setErrorMessage"](null);
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
