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
          <b-form
            @submit.prevent="onSubmit"
            @reset.prevent="onReset"
            v-if="show"
          >
            <b-form-group label="Eメールアドレス：" label-for="email">
              <b-form-input
                id="email"
                v-model="form.email"
                type="email"
                required
                placeholder="hoge@example.com"
              ></b-form-input>
            </b-form-group>

            <b-form-group
              label="パスワード："
              label-for="password"
              class="mb-4 mb-md-5"
            >
              <b-form-input
                id="password"
                v-model="form.password"
                type="password"
                required
                placeholder="パスワード（8から20文字の英数字）"
              ></b-form-input>
            </b-form-group>

            <div class="text-center mb-2">
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
        </b-card>
      </b-container>
    </main>
  </div>
</template>

<script>
import Header from './../components/Header.vue';

export default {
  name: 'Login',
  components: {
    Header
  },
  data() {
    return {
      form: {
        email: '',
        password: ''
      },
      show: true
    };
  },
  methods: {
    onSubmit() {
      alert(JSON.stringify(this.form));
    },
    onReset() {
      // Reset our form values
      this.form.email = '';
      this.form.password = '';
      // Trick to reset/clear native browser form validation state
      this.show = false;
      this.$nextTick(() => {
        this.show = true;
      });
    }
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
