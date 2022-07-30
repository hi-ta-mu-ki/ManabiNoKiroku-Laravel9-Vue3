<template>
  <div class="container-fluid bg-primary mb-3">
    <nav class="navbar navbar-primary">
      <span class="navbar-brand mb-0 h1 text-white">まなびのきろく３　きょうもがんばろう！
      <img src="/image/student_small.gif" border="0"></span>
      <div class="col-sm-2">
        <router-link v-bind:to="{name: 'st.join'}">
          <button class="btn btn-success me-1 text-white">クラスにさんか</button>
        </router-link>
        <button class="btn btn-secondary" @click="logout">ログアウト</button>
      </div>
    </nav>
  </div>
</template>

<script>
import { mapState } from 'vuex'
export default {
  computed: {
    ...mapState({
      apiStatus: state => state.auth_e_learning2.apiStatus
    })
  },
  methods: {
    async logout () {
      await this.$store.dispatch('auth_e_learning2/logout')
      if (this.apiStatus) {
        this.$router.push('/e_learning2/login')
      }
    },
  },
}
</script>