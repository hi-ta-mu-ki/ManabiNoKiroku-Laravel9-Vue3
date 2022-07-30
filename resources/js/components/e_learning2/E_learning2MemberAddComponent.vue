<template>
  <div class="container-fluid">
    <h2 class="title text-dark">メンバー追加</h2>
    <form @submit.prevent="submit">
      <div class="form-group row">
        <input type="text" v-model="keyword">
      </div>
      <div class="text-secondary">
        <table>
          <tr v-for="users in filteredUsers" :key="users.id">
            <td v-text="users.name"></td>
          </tr>
        </table>
      </div>
      <div v-if="isMsg"><div class="alert alert-danger" role="alert">{{ msg }}</div></div>
      <div v-if="cnt == 1">
        <button type="submit" class="btn btn-primary">決定</button>
      </div>
      <div v-else>
        <button class="btn btn-secondary" @click="clickEvent">キャンセル</button>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  name: 'MemberAdd',
  props: {
    e_classes_id: 0
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
    getUsers() {
      axios.get('/api/e_learning2/class_join1')
        .then((res) => {
          this.users = res.data
        });
    },
    clickEvent: function() {
      this.$emit('from-child')
    },
    submit() {
      axios.post('/api/e_learning2/class_join1/' + this.e_classes_id, this.joinForm)
        .then((res) => {
          if(res.status== 201)
            this.$emit('from-child')
        })
        .catch((error) => {
            this.isMsg = true
            this.msg = '登録できません'
        })
    },
  },
  computed: {
    filteredUsers: function() {
      var users = [];
      for(var i in this.users) {
          var user  = this.users[i]
          if(user.name.indexOf(this.keyword) !== -1) {
              users.push(user)
          }
      }
      this.cnt = users.length
      if(this.cnt == 1) this.joinForm.id = users[0].id
      this.isMsg = false
      return users
    }
  },
  mounted() {
    this.getUsers()
  }
}
</script>