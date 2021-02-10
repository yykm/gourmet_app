import Vue from 'vue';
import axios from 'axios';
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';
import './bootstrap';
import { extend, localize } from 'vee-validate';
import ja from "vee-validate/dist/locale/ja.json";
import * as rules from 'vee-validate/dist/rules';
import * as VueGoogleMaps from 'vue2-google-maps';


Vue.config.productionTip = false;

/**
 * プラグインの初期設定
 */

 /**  axios */
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';


/**  VeeValidate */
// バリデーションルールをインストール
Object.keys(rules).forEach(rule => {
  extend(rule, rules[rule]);
});
// 日本語にローカライズ
localize("ja", ja);



/**
 * プラグインの使用宣言
 */

  
/**Google Map API */
Vue.use(VueGoogleMaps, {
  load: {
    key: process.env.MIX_APP_GOOGLE_MAP_API,
    libraries: 'places',
    region: 'JP',
    language: 'ja'
  }
})
/** Bootstrap */
Vue.use(BootstrapVue, IconsPlugin); // BootstrapVue
