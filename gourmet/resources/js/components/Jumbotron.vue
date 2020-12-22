<template>
  <header class="jumbotron jumbotron-extend text-center m-0">
    <div class="jumbotron-container p-4 p-md-5 shadow">
      <h1 class="display-4 site-name">{{ site_name }}</h1>
      <p v-if="isLogin">
        <b-button @click.prevent="onClick" class="btn-black px-2 px-md-4 mx-2 mt-md-1"
          >ログアウト</b-button
        >
      </p>
      <span v-if="isLogin">
        {{ userName }}さん、ログイン中
      </span>
      <p v-else>
        <b-button to="/login" class="btn-black px-2 px-md-4 mx-2 mt-md-1"
          >ログイン</b-button
        >
      </p>
    </div>
  </header>
</template>

<script>
import { APP, ERR } from "./../store/const.js";
import { mapGetters, mapActions } from 'vuex';

export default {
  name: 'Jumbotron',
  props: {
    site_name: {
      type: String,
      default: 'site_name'
    }
  },
  computed: {
    ...mapGetters({
      isLogin: APP.getAppURI(APP.IS_LOGIN),
      userName:APP.getAppURI(APP.USER_NAME),
      apiStatus: APP.getAppURI(APP.GET_API_STATUS)
    }),
  },
  methods: {
    ...mapActions({
      logout: APP.getAppURI(APP.LOGOUT)
    }),

    // ログアウト
    async onClick(){
      // ストアのlogoutアクションを呼び出す
      await this['logout']();

      // API通信成功時
      if(this['apiStatus']){
        // トップページに移動する
        this.$router.push('/login');
      }
    }
  }
};
</script>

<style scoped>
.jumbotron-extend {
  position: relative;
  min-height: 300px;
  height: 50vh;
  background: url(/img/pizza.jpg) no-repeat center center;
  background-size: cover;
  border-radius: 0%;
}
@media (min-width: 768px) {
  .jumbotron-extend {
    height: 100vh;
  }
}

.jumbotron-container {
  /* ヘッダー文字中央揃え */
  position: relative;
  top: 50%;
  transform: translateY(-50%);
  /* ヘッダー背景 */
  background-color: rgba(255, 255, 255, 0.6);
  display: inline-block;
  border-radius: 50%;
}

.site-name {
  margin-bottom: 35px;
  color: #2e210fe5;
}
@media (min-width: 768px) {
  .site-name {
    font-size: 5rem;
    font-weight: 300;
    line-height: 1.2;
  }
}

.btn-black {
  /* border-radius: 0; */
  background-color: #000;
  color: #fff;
  font-family: 'Avenir', serif;
  border-color: transparent;
}
.btn-black:hover {
  background-color: #fff;
  color: #000;
}
</style>
