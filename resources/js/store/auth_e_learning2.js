import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../util'

const state = {
  user: null,
  apiStatus: null,
  loginErrorMessages: null,
  e_groups_id: null,
  e_classes_id: null
}

const getters = {
  check: state => !! state.user,
  username: state => state.user ? state.user.name : '',
  role: state => state.user ? state.user.role : 0,
  id: state => state.user ? state.user.id : 0,
  e_groups_id: state => state.e_groups_id ? state.e_groups_id : 0,
  e_classes_id: state => state.e_classes_id ? state.e_classes_id : 0,
}

const mutations = {
  setUser (state, user) {
    state.user = user
  },
  setApiStatus (state, status) {
    state.apiStatus = status
  },
  setLoginErrorMessages (state, messages) {
    state.loginErrorMessages = messages
  },
  setE_Groups_Id (state, e_groups_id) {
    state.e_groups_id = e_groups_id
  },
  setE_Classes_Id (state, e_classes_id) {
    state.e_classes_id = e_classes_id
  },
}

const actions = {
  // ログイン
  async login (context, data) {
    context.commit('setApiStatus', null)
    const response = await axios.post('/api/e_learning2/login', data)

    if (response.status === OK) {
      context.commit('setApiStatus', true)
      response.data.password_raw = null
      context.commit('setUser', response.data)
      return false
    }

    context.commit('setApiStatus', false)
    if (response.status === UNPROCESSABLE_ENTITY) {
      context.commit('setLoginErrorMessages', response.data.errors)
    } else {
      context.commit('error/setCode', response.status, { root: true })
    }
  },

  // ログアウト
  async logout (context) {
    context.commit('setApiStatus', null)
    const response = await axios.post('/api/e_learning2/logout')

    if (response.status === OK) {
      context.commit('setApiStatus', true)
      context.commit('setUser', null)
      return false
    }

    context.commit('setApiStatus', false)
    context.commit('error/setCode', response.status, { root: true })
  },

  // ログインユーザーチェック
  async currentUser (context) {
    context.commit('setApiStatus', null)
    const response = await axios.get('/api/e_learning2/user')
    const user = response.data || null

    if (response.status === OK) {
      context.commit('setApiStatus', true)
      context.commit('setUser', user)
      return false
    }

    context.commit('setApiStatus', false)
    context.commit('error/setCode', response.status, { root: true })
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}