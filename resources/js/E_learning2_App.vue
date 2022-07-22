<template>
  <div>
    <main>
      <div class="container-fluid">
        <RouterView />
      </div>
    </main>
  </div>
</template>

<script>
import { NOT_FOUND, UNAUTHORIZED, INTERNAL_SERVER_ERROR } from './util'
export default {
  computed: {
    errorCode () {
      return this.$store.state.error.code
    }
  },
  watch: {
    errorCode: {
      async handler (val) {
        if (val === INTERNAL_SERVER_ERROR) {
          this.$router.push('/500')
        } else if (val === UNAUTHORIZED) {
          // トークンをリフレッシュ
          await axios.get('/api/e_learning2/refresh-token')
          // ストアのuserをクリア
          this.$store.commit('auth_e_learning2/setUser', null)
          // ログイン画面へ
          this.$router.push('/e_learning2/login')
        } else if (val === NOT_FOUND) {
          this.$router.push('/not-found')
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