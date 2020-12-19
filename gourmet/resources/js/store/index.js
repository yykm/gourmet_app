import Vue from 'vue';
import Vuex from 'vuex';
import App from './app';
import Err from './err';

Vue.use(Vuex);

export default new Vuex.Store({
  strict: true,
  modules: {
    App,
    Err
  }
});
