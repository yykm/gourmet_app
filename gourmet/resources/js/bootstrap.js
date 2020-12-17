import Vue from 'vue';
import axios from 'axios';
import VueAxios from 'vue-axios';
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';
import './bootstrap';

Vue.config.productionTip = false;

/**
 * プラグインの初期設定
 */
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * プラグインの使用宣言
 */
// axios
Vue.use(VueAxios, axios);

// BootstrapVue
Vue.use(BootstrapVue, IconsPlugin);
