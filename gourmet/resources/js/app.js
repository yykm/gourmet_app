import Vue from 'vue';
import router from './router';
import store from './store';
import App from './App.vue';
import './bootstrap';

// Vue 起動時にログイン状態を取得してからVueインスタンス生成
const createApp = async () => {
  await store.dispatch('App/currentUser');

  new Vue({
    router,
    store,
    render: h => h(App)
  }).$mount('#app');
}

createApp();
