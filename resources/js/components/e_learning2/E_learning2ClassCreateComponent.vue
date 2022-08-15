<template>
  <div class="container">
    <h2 class="title">クラス追加</h2>
    <div class="row justify-content-center">
      <div class="row mb-1">
        <table border=1><tr><td>
          【ヘルプ】　※パスコードが空欄の場合は，パスコードでの参加はできません。<br>
          　　　　　　　また，セキュリティ上，パスコードの設定から10日後は無効となります。
        </td></tr></table>
      </div>
      <div class="col-sm-8">
        <div>
          {{ group.name }}
        </div>
        <form @submit.prevent="submit">
          <div class="form-group row">
            <label for="name" class="col-sm-2 col-form-label">クラス名</label>
            <input type="text" class="col-sm-6 form-control" id="name" v-model="clas.name">
          </div>
          <div class="form-group row">
            <label for="pass_code" class="col-sm-2 col-form-label">パスコード</label>
            <input type="text" class="col-sm-6 form-control" id="pass_code" v-model="clas.pass_code">
          </div>
          <div v-if="isMsg"><div class="alert alert-danger" role="alert">{{ msg }}</div></div>
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
    e_groups_id: 0
  },
  data: function () {
    return {
      clas: {},
      group: [],
      msg: '',
      isMsg: false,
      user_id: 0
    }
  },
  methods: {
    getGroup() {
      axios.get('/api/e_learning2/group/' + this.e_groups_id)
        .then((res) => {
          this.group = res.data
        })
    },
    submit() {
      this.user_id = this.$store.getters['auth_e_learning2/id']
      this.clas.e_groups_id = this.e_groups_id
      axios.post('/api/e_learning2/class/' + this.user_id, this.clas, )
        .then((res) => {
          if(res.status== 201)
            this.$router.push({name: 'tc.classlist'})
          else{
            this.isMsg = true
            this.msg = 'すでに使用されているクラス名，または，パスコードです'
          }
        })
    }
  },
  mounted() {
    this.getGroup()
  }
}
</script>