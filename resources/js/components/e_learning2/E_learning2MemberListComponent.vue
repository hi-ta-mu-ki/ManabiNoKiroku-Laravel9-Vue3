<template>
  <div class="container">
    <h2 class="title">クラス生徒一覧</h2>
    <div class="bg-primary mb-1">
      <div class="p-1 mb-1 bg-primary text-white form-inline row">
        <div class="row">
          <label for="selectclass" class="col-form-label text-end me-1 col-sm-2">クラスを選択：</label>
          <div class="col-sm-4">
            <select class=" form-select" id="selectclass" @change="jump" v-model="e_classes_id">
              <option v-for="classes_menu in classes_menus" :key="classes_menu.id" v-bind:value="classes_menu.id" >{{ classes_menu.name }}</option>
            </select>
          </div>
          <div v-if="isClassSelect" class="col-sm-4">
            <button class="btn btn-success me-2 col-sm-4 text-white" data-bs-toggle="modal" data-bs-target="#memberadd_Modal">
              メンバーを追加
            </button>
            <div class="modal fade" id="memberadd_Modal" tabindex="-1" aria-labelledby="memberadd_ModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content text-black">
                  <div class="modal-header">
                    <h5 class="modal-title" id="memberadd_ModalLabel">メンバーを追加</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <MemberAdd :e_classes_id="e_classes_id" />
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-primary" @click="member_update" data-bs-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div v-if="isClassSelect">
      <table class="table table-hover">
        <thead class="thead-light">
        <tr>
          <th scope="col">名前</th>
          <th scope="col">役割</th>
          <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
          <tr v-for="user in users" :key="user.user.id">
            <td>{{ user.user.name }}</td>
            <td>
              <div v-if="user.user.role == 1">管理者</div>
              <div v-else-if="user.user.role == 5">教員</div>
              <div v-else>生徒</div>
            </td>
            <td>
              <button class="btn btn-danger text-white" @click="member_delete(user.user_id)" data-bs-toggle="modal" data-bs-target="#memberdelete_Modal">削除</button>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="modal fade" id="memberdelete_Modal" tabindex="-1" aria-labelledby="memberdelete_ModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content text-black">
            <div class="modal-header">
              <h5 class="modal-title" id="memberdelete_ModalLabel">メンバー削除</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              この生徒の成績も削除します。よろしいですか？
            </div>
            <div class="modal-footer">
              <button class="btn btn-primary" @click="delete_go" data-bs-dismiss="modal">OK</button>
              <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import MemberAdd from './E_learning2MemberAddComponent.vue'
export default {
  components: {
    MemberAdd
  },
  data: function () {
    return {
      classes_menus: [],
      isClassSelect: false,
      users: [],
      e_classes_id: 0,
      del_id: 0
    }
  },
  methods: {
    getClassesMenu() {
      axios.get('/api/e_learning2/classes_menu')
        .then((res) => {
          this.classes_menus = res.data
        });
    },
    getUsers() {
      axios.get('/api/e_learning2/member_list/' + this.e_classes_id)
        .then((res) => {
          this.users = res.data
        });
    },
    member_update() {
      this.getUsers()
    },
    member_delete:function(id) {
      this.del_id = id
    },
    delete_go() {
      axios.delete('/api/e_learning2/st/answer/' + this.del_id)
      axios.delete('/api/e_learning2/member_list/' + this.del_id)
      this.getUsers()
    },
    jump: function() {
      this.$store.commit('auth_e_learning2/setE_Classes_Id', this.e_classes_id)
      this.isClassSelect = true
      this.getUsers()
    },
  },
  mounted() {
    this.getClassesMenu()
  }
}
</script>