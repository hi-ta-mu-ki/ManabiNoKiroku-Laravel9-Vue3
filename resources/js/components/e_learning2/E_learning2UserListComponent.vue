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
            <button class="btn btn-danger text-white" @click="user_delete(user.id)" data-bs-toggle="modal" data-bs-target="#userdelete_Modal">削除</button>
          </td>
        </tr>
      </tbody>
    </table>
    <div class="modal fade" id="userdelete_Modal" tabindex="-1" aria-labelledby="userdelete_ModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content text-black">
          <div class="modal-header">
            <h5 class="modal-title" id="userdelete_ModalLabel">ユーザ削除</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            このユーザの情報をすべて削除します。よろしいですか？
          </div>
          <div class="modal-footer">
            <button class="btn btn-primary" @click="delete_go" data-bs-dismiss="modal">OK</button>
            <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data: function () {
    return {
      users: [],
      del_id: 0
    }
  },
  methods: {
    getUsers() {
      axios.get('/api/e_learning2/ad')
        .then((res) => {
          this.users = res.data;
          for(var i = 0; i < this.users.length; i++)
            if(this.users[i].role != 10) this.users[i].password = null
        });
    },
    user_update() {
      this.getUsers()
    },
    user_delete:function(id) {
      this.del_id = id
    },
    delete_go() {
      axios.delete('/api/e_learning2/ad/' + this.del_id)
      this.getUsers()
    },
  },
  mounted() {
    this.getUsers()
  }
}
</script>