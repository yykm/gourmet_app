<template>
  <div class="register">
    <Header :site_name="'Gourmet'"></Header>

    <main class="py-4">
      <b-container>
        <b-card class="mx-auto" tag="main">
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
                  <b-form-invalid-feedback>
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                  <b-form-valid-feedback
                    >有効な名前です。</b-form-valid-feedback
                  >
                </b-form-group>
              </validation-provider>

              <!-- メールアドレス -->
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
                  <b-form-invalid-feedback>
                    有効なメールアドレスではありません。
                  </b-form-invalid-feedback>
                  <b-form-valid-feedback
                    >有効なメールアドレスです。</b-form-valid-feedback
                  >
                </b-form-group>
              </validation-provider>

              <!-- パスワード  -->
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
                  <b-form-invalid-feedback>
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                  <b-form-valid-feedback
                    >有効なパスワードです。</b-form-valid-feedback
                  >
                </b-form-group>
              </validation-provider>

              <!-- パスワード確認用 -->
              <validation-provider
                :rules="{
                  required: true,
                  alpha_num: true,
                  min: 8,
                  max: 20,
                  confirmed: 'form.password'
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
                  <b-form-invalid-feedback>
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                  <b-form-valid-feedback>一致しました</b-form-valid-feedback>
                </b-form-group>
              </validation-provider>

              <div v-if="registerErrors" class="errors text-danger text-center">
                {{ registerErrors }}
              </div>

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
    Header,
    ValidationObserver,
    ValidationProvider
  },
  data() {
    return {
      form: {
        name: "",
        email: "",
        password: "",
        password_confirmation: ""
      },
      password: ""
    };
  },
  computed: {
    ...mapGetters({
      apiStatus: APP.getAppURI(APP.GET_API_STATUS),
      registerErrors: ERR.getErrURI(ERR.GET_REGISTER_ERROR_MESSAGE)
    })
  },
  methods: {
    ...mapActions({
      regist: APP.getAppURI(APP.REGISTER),
      setRegisterErrorMessage: ERR.getErrURI(ERR.SET_REGISTER_ERROR_MESSAGE)
    }),

    getValidationState({ dirty, validated, valid = null }) {
      return dirty || validated ? valid : null;
    },

    // ユーザ登録
    async onSubmit() {
      // ストアのsetUserアクションを呼び出す
      await this["regist"](this.form);

      // API通信成功時
      if (this["apiStatus"]) {
        // トップページに移動する
        this.$router.push("/");
      }
    },
    onReset() {
      // Reset our form values
      this.form.name = "";
      this.form.email = "";
      this.form.password = "";
      this.form.password_confirmation = "";
      // Trick to reset/clear native browser form validation state
      this["setRegisterErrorMessage"](null);
      this.$nextTick(() => {
        this.$refs.observer.reset();
      });
    }
  },
  created() {
    this["setRegisterErrorMessage"](null);
  }
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
