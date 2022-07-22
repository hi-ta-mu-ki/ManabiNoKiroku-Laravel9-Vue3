<template>
  <div class="container-fluid bg-dark mb-3">
    <nav class="navbar navbar-dark">
      <span class="navbar-brand mb-0 ms-5 h1">まなびのきろく３　ユーザ管理</span>
      <div>
        <router-link v-bind:to="{name: 'ad.userlist'}">
          <button class="btn btn-success text-white">ユーザ一覧</button>
        </router-link>
        <router-link v-bind:to="{name: 'ad.usercreate'}">
          <button class="btn btn-success text-white">ユーザ追加</button>
        </router-link>
        <router-link v-bind:to="{name: 'ad.userimport'}">
          <button class="btn btn-success text-white">CSV インポート</button>
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
    }
  }
}
</script>