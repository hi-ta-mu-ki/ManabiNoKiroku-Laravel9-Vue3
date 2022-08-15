<template>
  <div class="container">
    <h2 class="title">クラスにさんかする</h2>
    <div class="row justify-content-center">
      <div class="col-sm-10">
        <form @submit.prevent="submit">
          <div class="row mb-2">
            <label for="pass_code" class="col-form-label col-sm-2 mb-2">パスコード</label>
            <div class="col-sm-4">
              <input type="text" class="form-control col-sm-6" id="pass_code" v-model="joinForm.pass_code" placeholder="参加のためのパスコードを入力">
            </div>
          </div>
          <div v-if="isMsg" class="row">
            <div class="alert alert-danger" role="alert">{{ msg }}</div>
          </div>
          <button type="submit" class="btn btn-primary me-1">さんかする</button>
          <button type="button" class="btn btn-secondary" @click="cansel">キャンセル</button>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data: function () {
    return {
      joinForm: {},
      msg: '',
      isMsg: false,
      user_id: 0
    }
  },
  methods: {
    submit() {
      axios.post('/api/e_learning2/class_join_self/' + this.user_id, this.joinForm)
        .then((res) => {
          if(res.status == 201){
            if (this.$store.getters['auth_e_learning2/role'] == 5)
              this.$router.push(`/e_learning2/tc`)
            else if (this.$store.getters['auth_e_learning2/role'] == 10)
              this.$router.push(`/e_learning2/st`)
          }else{
            this.isMsg = true
            this.msg = 'パスコードがむこうか，すでにとうろくしています'
          }
        })
    },
    cansel: function() {
            if (this.$store.getters['auth_e_learning2/role'] == 5)
              this.$router.push(`/e_learning2/tc`)
            else if (this.$store.getters['auth_e_learning2/role'] == 10)
              this.$router.push(`/e_learning2/st`)
    }
  },
  mounted(){
    this.user_id = this.$store.getters['auth_e_learning2/id']
  }
}
</script>