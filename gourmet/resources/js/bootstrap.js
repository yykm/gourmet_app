import Vue from 'vue';
import axios from 'axios';
import { BootstrapVue } from 'bootstrap-vue';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';
import './bootstrap';
import { extend, localize } from 'vee-validate';
import ja from "vee-validate/dist/locale/ja.json";
import * as rules from 'vee-validate/dist/rules';
import * as VueGoogleMaps from 'vue2-google-maps';


/**  Vue の起動時のプロダクションのヒントを非表示 */
Vue.config.productionTip = false;

/** axios初期設定 */
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/** VeeValidate初期設定 */
Object.keys(rules).forEach(rule => {
  extend(rule, rules[rule]);
});
localize("ja", ja);

/** vue2-google-mapsプラグイン使用宣言 */
Vue.use(VueGoogleMaps, {
  load: {
    key: process.env.MIX_APP_GOOGLE_MAP_API,
    libraries: 'places',
    region: 'JP',
    language: 'ja'
  }
})

/** Bootstrapプラグイン使用宣言 */
Vue.use(BootstrapVue);
