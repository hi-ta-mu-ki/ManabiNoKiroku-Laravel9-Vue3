<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-sm-12">
        <form @submit.prevent="submit">
          <div class="form-group row">
            <label for="name" class="col-sm-2 col-form-label">名前</label>
            <input type="text" class="col-sm-10 form-control" id="name" v-model="user.name">
          </div>
          <div class="form-group row">
            <label for="email" class="col-sm-2 col-form-label">メールアドレス</label>
            <input type="text" class="col-sm-10 form-control" id="email" v-model="user.email">
          </div>
          <div class="form-group row">
            <label for="password" class="col-sm-2 col-form-label text-primary">パスワード</label>
            <input type="text" class="col-sm-10 form-control text-primary" id="password" v-model="user.password">
          </div>
          <div class="form-group row">
            <label for="role" class="col-sm-2 col-form-label">役割</label>
            <select class="col-sm-10 form-control" v-model="user.role">
              <option value="">Select Role</option>
              <option v-bind:value="1">管理者</option>
              <option v-bind:value="5">教　員</option>
              <option v-bind:value="10">生　徒</option>
            </select>
          </div>
          <div class="mt-4">
            <button type="submit" class="btn btn-primary">決定</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    userId: 0
  },
  data: function () {
    return {
      user: {},
    }
  },
  methods: {
    getUser() {
      axios.get('/api/e_learning2/ad/' + this.userId)
        .then((res) => {
          this.user = res.data
          if(this.user.role < 6) this.user.password = null
          else this.user.password = this.user.password_raw
        })
    },
    submit() {
      axios.put('/api/e_learning2/ad/' + this.userId, this.user)
        .then((res) => {
          this.$router.push({name: 'ad.userlist'})
        });
    }
  },
  mounted() {
    this.getUser();
  }
}
</script>