<template>
  <div class="container">
    <h2 class="title">グループ所有者一覧</h2>
    <div class="bg-primary mb-1">
      <div class="p-1 mb-1 bg-primary row">
        <div class="col-4">
          <button class="btn btn-success me-2 col-sm-4 text-white" data-bs-toggle="modal" data-bs-target="#owneradd_Modal">
            所有者を追加
          </button>
          <div class="modal fade" id="owneradd_Modal" tabindex="-1" aria-labelledby="owneradd_ModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content text-black">
                <div class="modal-header">
                  <h2 class="modal-title" id="owneradd_ModalLabel">所有者を追加</h2>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <OwnerAdd :e_groups_id="e_groups_id" />
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-primary" @click="owner_update" data-bs-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <table class="table table-hover">
      <thead class="thead-light">
      <tr>
        <th scope="col">名前</th>
        <th scope="col"></th>
      </tr>
      </thead>
      <tbody>
        <tr v-for="user in users" :key="user.user.id">
          <td>{{ user.user.name }}</td>
          <td>
            <div v-if="cnt > 1">
              <button class="btn btn-danger text-white" @click="owner_delete(user.user_id)" data-bs-toggle="modal" data-bs-target="#ownerdelete_Modal">削除</button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
    <div class="modal fade" id="ownerdelete_Modal" tabindex="-1" aria-labelledby="ownerdelete_ModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content text-black">
          <div class="modal-header">
            <h5 class="modal-title" id="ownerdelete_ModalLabel">所有者削除</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            削除します。よろしいですか？
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
import OwnerAdd from './E_learning2OwnerAddComponent.vue'
export default {
  components: {
    OwnerAdd
  },
  props: {
    e_groups_id: 0
  },
  data: function () {
    return {
      users: [],
      cnt: 0,
      user_id: 0

    }
  },
  methods: {
    getUsers() {
      axios.get('/api/e_learning2/owner_list/' + this.e_groups_id)
        .then((res) => {
          this.users = res.data
          this.cnt = this.users.length
        })
    },
    owner_update() {
      this.getUsers()
    },
    owner_delete:function(user_id) {
      this.user_id = user_id
    },
    delete_go() {
      axios.delete('/api/e_learning2/owner_list/' + this.e_groups_id + '/' + this.user_id)
      this.getUsers()
    },
  },
  mounted() {
    this.$store.commit('auth_e_learning2/setE_Groups_Id', this.e_groups_id)
    this.getUsers()
  }
}
</script>