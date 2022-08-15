<template>
  <div class="container-fluid">
    <div class="bg-primary mb-3">
      <nav class="navbar navbar-primary">
        <span class="navbar-brand mb-0 ms-5 h1 text-white">まなびのきろく３　ログイン</span>
      </nav>
    </div>
    <div class="text-center">
      <img src="/image/title3.jpg">
    </div>
    <div class="text-center">
      <img src="/image/student.gif">
    </div>
    <div class="row justify-content-center">
      <div class="col-sm-3">
        <div class="card">
          <div class="card-body">
            <form class="form" @submit.prevent="login">
              <div class="row mb-3">
                <label for="login-email" class="col-form-label col-sm-4 text-sm-end">メールアドレス</label>
                <input type="text" class="col-sm-6" id="login-email" v-model="loginForm.email" placeholder="ｅ－Ｍａｉｌ">
              </div>
              <div class="row mb-3">
                <label for="login-password" class="col-form-label col-sm-4 text-sm-end">パスワード</label>
                <input type="password" class="col-sm-6" id="login-password" v-model="loginForm.password" autocomplete="off" placeholder="パスワード">
              </div>
              <div v-if="loginErrors" class="errors">
                <div v-if="loginErrors.email" class="alert alert-danger mb-3">
                  <div v-for="msg in loginErrors.email" :key="msg">{{ msg }}</div>
                </div>
                <div v-if="loginErrors.password" class="alert alert-danger mb-3">
                  <div v-for="msg in loginErrors.password" :key="msg">{{ msg }}</div>
                </div>
              </div>
              <div class="row">
                <div class="text-center">
                  <button type="submit" class="btn btn-primary">ログイン</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
export default {
  data () {
    return {
      loginForm: {
        email: '',
        password: ''
      }
  }
  },
  computed: mapState({
    apiStatus: state => state.auth_e_learning2.apiStatus,
    loginErrors: state => state.auth_e_learning2.loginErrorMessages,
  }),
  methods: {
    async login () {
      // authストアのloginアクションを呼び出す
      await this.$store.dispatch('auth_e_learning2/login', this.loginForm)
      if (this.apiStatus) {
        // トップページに移動する
        if (this.$store.getters['auth_e_learning2/role'] == 5)
          this.$router.push(`/e_learning2/tc`)
        else if (this.$store.getters['auth_e_learning2/role'] == 10)
          this.$router.push(`/e_learning2/st`)
        else if (this.$store.getters['auth_e_learning2/role'] == 1)
          this.$router.push(`/e_learning2/ad`)
      }
    },
    clearError () {
      this.$store.commit('auth_e_learning2/setLoginErrorMessages', null)
    }
  },
  created () {
    this.clearError()
  }
}
</script>