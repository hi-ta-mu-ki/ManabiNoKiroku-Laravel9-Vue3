<template>
  <div class="container">
    <table class="table table-hover">
      <thead class="thead-light">
        <tr>
          <th scope="col">ID</th>
          <th scope="col">名前</th>
          <th scope="col">メールアドレス</th>
          <th scope="col">パスワード</th>
          <th scope="col">役割</th>
          <th scope="col"></th>
          <th scope="col"></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="user in users" :key="user.id">
          <th scope="row">{{ user.id }}</th>
          <td>{{ user.name }}</td>
          <td>{{ user.email }}</td>
          <td>
            <div v-if="user.role != 10">********</div>
            <div v-else>{{ user.password_raw }}</div>
          </td>
          <td>
            <div v-if="user.role == 1">管理者</div>
            <div v-else-if="user.role == 5">教員</div>
            <div v-else>生徒</div>
          </td>
          <td>
            <router-link v-bind:to="{name: 'ad.useredit', params: {userId: user.id }}">
              <button class="btn btn-success text-white">編集</button>
            </router-link>
          </td>
          <td>
            <button class="btn btn-danger text-white" v-confirm="onAlert(user.id)">削除</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  data: function () {
    return {
      users: [],
    }
  },
  methods: {
    getusers() {
      axios.get('/api/e_learning2/ad')
        .then((res) => {
          this.users = res.data;
          for(var i = 0; i < this.users.length; i++)
            if(this.users[i].role != 10) this.users[i].password = null
        });
    },
    makeAdmin:function(dialog, id) {
      axios.delete('/api/e_learning2/ad/' + id)
      this.getusers()
      dialog.close()
    },
    doNothing:function() {
    },
    onAlert:function(id) {
      let self = this
      return {
          ok: function(dialog){self.makeAdmin(dialog, id)},
          cancel: this.doNothing(),
          message: {
            title: '確認',
            body: 'このユーザの情報をすべて削除します。よろしいですか？'
          }
      };
    }
  },
  mounted() {
    this.getusers()
  }
}
</script>