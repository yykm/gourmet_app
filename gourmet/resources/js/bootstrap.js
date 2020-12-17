import Vue from 'vue';
import axios from 'axios';
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';
import './bootstrap';

Vue.config.productionTip = false;

/**
 * プラグインの使用宣言
 */
Vue.use(BootstrapVue, IconsPlugin); // BootstrapVue

/**
 * プラグインの初期設定
 */
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
