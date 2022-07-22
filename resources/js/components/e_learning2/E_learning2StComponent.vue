<template>
  <div>
    <header>
      <Header />
    </header>
    <main>
      <div class="container-fluid">
        <RouterView />
      </div>
    </main>
  </div>
</template>

<script>
import { NOT_FOUND, UNAUTHORIZED, INTERNAL_SERVER_ERROR } from '../../util'
import Header from './E_learning2StHeaderComponent.vue'
export default {
  components: {
    Header,
  },
  watch: {
    errorCode: {
      async handler (val) {
        if (val === INTERNAL_SERVER_ERROR) {
          this.$router.push('/e_learning2/500')
        } else if ((val === UNAUTHORIZED) ||
            !this.$store.getters['auth_e_learning2/check'] ||
            this.$store.getters['auth_e_learning2/role'] != 10) {
          // トークンをリフレッシュ
          await axios.get('/api/e_learning2/refresh-token')
          // ストアのuserをクリア
          this.$store.commit('auth_e_learning2/setUser', null)
          // ログイン画面へ
          this.$router.push('/e_learning2/login')
        } else if (val === NOT_FOUND) {
          this.$router.push('/e_learning2/not-found')
        }
      },
      immediate: true
    },
    $route () {
      this.$store.commit('error/setCode', null)
    },
  }
}
</script>