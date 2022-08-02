<template>
  <div class="container">
    <h5 class="title text-dark">文字を入力すると対象者を検索します。</h5>
    <form @submit.prevent="submit">
      <div class="form-group row">
        <input type="text" v-model="keyword">
      </div>
      <div v-if="cnt > 0" class="text-secondary">
        <table>
          <tr v-for="user in users" :key="user.id">
            <td v-text="user.name"></td>
          </tr>
        </table>
      </div>
      <div v-else>未入力　または　該当者なし</div>
      <div v-if="isMsg"><div class="alert alert-danger" role="alert">{{ msg }}</div></div>
      <div v-if="cnt == 1">
        <button type="submit" class="btn btn-primary">決定</button>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  props: {
    e_groups_id: 0
  },
  data: function () {
    return {
      users: [],
      keyword: "",
      cnt: 0,
      joinForm: {
        id: 0
      },
      msg: '',
      isMsg: false
    }
  },
  methods: {
    submit() {
      axios.post('/api/e_learning2/group_join/' + this.e_groups_id, this.joinForm)
        .catch((error) => {
            this.isMsg = true
            this.msg = '登録できません'
        })
    },
  },
  watch: {
    keyword() {
      axios.get('/api/e_learning2/group_join/' + this.e_groups_id + '/' + this.keyword)
        .then((res) => {
          this.users = res.data
          this.cnt = this.users.length
          if(this.cnt == 1) this.joinForm.id = this.users[0].id
          this.isMsg = false
        });
    }
  }
}
</script>