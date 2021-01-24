import Vue from 'vue';
import Vuex from 'vuex';
import Err from './err';
import App from './app';
import Message from './message';

Vue.use(Vuex);

export default new Vuex.Store({
  strict: true,
  modules: {
    App,
    Err,
    Message
  }
});
