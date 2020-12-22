import Vue from 'vue';
import axios from 'axios';
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';
import './bootstrap';
import { ValidationObserver, ValidationProvider, extend, localize } from 'vee-validate';
import ja from "vee-validate/dist/locale/ja.json";
import * as rules from 'vee-validate/dist/rules';

/**
 * プラグインの初期設定
 */
// axios
 window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// VeeValidate
// バリデーションルールをインストール
Object.keys(rules).forEach(rule => {
  extend(rule, rules[rule]);
});
// 日本語にローカライズ
localize("ja", ja);

Vue.config.productionTip = false;

/**
 * プラグインの使用宣言
 */
Vue.use(BootstrapVue, IconsPlugin); // BootstrapVue
Vue.component('ValidationProvider', ValidationProvider);
Vue.component('ValidationObserver', ValidationObserver);
