<template>
  <div class="container">
    <div class="row mb-3">
      <form @submit.prevent="submit">
        <div class="col-sm-4 input-group">
          <input type="text" class="form-control" v-model="keyword" placeholder="検索キーワード">
          <button type="submit" class="btn btn-info text-white">検索</button>
        </div>
      </form>
    </div>
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
            <router-link
              v-bind:to="{ name: 'ad.useredit', params: { userId: user.id } }"
            >
              <button class="btn btn-success text-white">編集</button>
            </router-link>
          </td>
          <td>
            <button
              class="btn btn-danger text-white"
              @click="user_delete(user.id)"
              data-bs-toggle="modal"
              data-bs-target="#userdelete_Modal"
            >
              削除
            </button>
          </td>
        </tr>
      </tbody>
    </table>
    <div class="row">
      <div class="col-sm-6">
        <ul class="pagination">
          <li :class="{ disabled: current_page <= 1 }">
            <a class="page-link" href="#" @click="change(1)">&laquo;</a>
          </li>
          <li :class="{ disabled: current_page <= 1 }">
            <a class="page-link" href="#" @click="change(current_page - 1)">&lt;</a>
          </li>
          <li
            v-for="page in pages"
            :key="page"
            :class="{ active: page === current_page }"
          >
            <a class="page-link" href="#" @click="change(page)">{{ page }}</a>
          </li>
          <li :class="{ disabled: current_page >= last_page }">
            <a class="page-link" href="#" @click="change(current_page + 1)">&gt;</a>
          </li>
          <li :class="{ disabled: current_page >= last_page }">
            <a class="page-link" href="#" @click="change(last_page)">&raquo;</a>
          </li>
        </ul>
      </div>
      <div style="margin-top: 40px" class="col-sm-6 text-right">
        全 {{ total }} 件中 {{ from }} 〜 {{ to }} 件表示
      </div>
    </div>
    <div
      class="modal fade"
      id="userdelete_Modal"
      tabindex="-1"
      aria-labelledby="userdelete_ModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content text-black">
          <div class="modal-header">
            <h5 class="modal-title" id="userdelete_ModalLabel">ユーザ削除</h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            このユーザの情報をすべて削除します。よろしいですか？
          </div>
          <div class="modal-footer">
            <button
              class="btn btn-primary"
              @click="delete_go"
              data-bs-dismiss="modal"
            >
              OK
            </button>
            <button class="btn btn-secondary" data-bs-dismiss="modal">
              Cancel
            </button>
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
      del_id: 0,
      current_page: 1,
      last_page: 1,
      total: 1,
      from: 0,
      to: 0,
      keyword: "",
    };
  },
  methods: {
    getUsers(page) {
      let api_url ="/api/e_learning2/ad?page=" + page
      if (this.keyword != "")
        api_url = "/api/e_learning2/ad/search/" + this.keyword + "?page=" + page
      axios.get(api_url)
        .then((res) => {
          this.users = res.data.data;
          for (var i = 0; i < this.users.length; i++)
            if (this.users[i].role != 10) this.users[i].password = null;
          this.current_page = res.data.current_page
          this.last_page = res.data.last_page
          this.total = res.data.total
          this.from = res.data.from
          this.to = res.data.to
      });
    },
    change(page) {
      if (page >= 1 && page <= this.last_page) this.getUsers(page);
    },
    submit(){
      this.getUsers(1)
    },
    user_delete: function (id) {
      this.del_id = id
    },
    delete_go() {
      axios.delete("/api/e_learning2/ad/" + this.del_id)
      this.getUsers(page);
    },
  },
  computed: {
    pages() {
      let start = _.max([this.current_page - 2, 1])
      let end = _.min([start + 5, this.last_page + 1])
      start = _.max([end - 5, 1])
      return _.range(start, end)
    },
  },
  mounted() {
    this.getUsers(1);
  },
};
</script>