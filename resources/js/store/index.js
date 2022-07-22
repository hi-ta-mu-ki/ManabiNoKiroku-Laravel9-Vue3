import Vue from 'vue'
import Vuex from 'vuex'

import error from './error'
import message from './message'
import auth_e_learning2 from './auth_e_learning2'

Vue.use(Vuex)

const store = new Vuex.Store({
  modules: {
    error,
    message,
    auth_e_learning2,
  }
})

export default store