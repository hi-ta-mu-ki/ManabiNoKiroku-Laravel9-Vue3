<template>
  <div class="container">
    <h2 class="title">クラス編集</h2>
    <div class="row justify-content-center">
      <div class="row mb-1">
        <table border=1><tr><td>
          【ヘルプ】　※パスコードが空欄の場合は，パスコードでの参加はできません。<br>
          　　　　　　　また，セキュリティ上，パスコードの設定から10日後は無効となります。
        </td></tr></table>
      </div>
      <div class="col-sm-8">
        <form @submit.prevent="submit">
          <div class="form-group row">
            <label for="name" class="col-sm-2 col-form-label">クラス名</label>
            <input type="text" class="col-sm-6 form-control" id="name" v-model="clas.name">
          </div>
          <div class="form-group row">
            <label for="pass_code" class="col-sm-2 col-form-label">パスコード</label>
            <input type="text" class="col-sm-6 form-control" id="pass_code" v-model="clas.pass_code">
          </div>
          <div v-if="isMsg"><div class="alert alert-danger mt-2" role="alert">{{ msg }}</div></div>
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
    classId: 0
  },
  data: function () {
    return {
      clas: {},
      msg: '',
      isMsg: false
    }
  },
  methods: {
    getclass() {
      axios.get('/api/e_learning2/class/' + this.classId)
        .then((res) => {
          this.clas = res.data
        })
    },
    submit() {
      axios.put('/api/e_learning2/class/' + this.classId, this.clas)
        .then((res) => {
          if(res.status== 200)
            this.$router.push({name: 'tc.classlist'})
        })
        .catch((error) => {
            this.isMsg = true
            this.msg = 'すでに使用されているクラス名，または，パスコードです';
        })
    }
  },
  mounted() {
    this.getclass()
  }
}
</script>