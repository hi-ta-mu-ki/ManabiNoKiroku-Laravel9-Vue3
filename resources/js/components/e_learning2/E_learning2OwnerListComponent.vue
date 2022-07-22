<template>
  <div class="container">
    <h2 class="title">グループ所有者一覧</h2>
    <div class="bg-primary mb-1">
      <div class="p-1 mb-1 bg-primary row">
        <div class="col-2">
          <button class="btn btn-success text-white" @click="openModal">所有者を追加</button>
          <OwnerAdd :e_groups_id="groupId" @from-child="closeModal" />
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
              <button class="btn btn-danger text-white" v-confirm="onAlert(user.user_id)">削除</button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import OwnerAdd from './E_learning2OwnerAddComponent.vue'
export default {
  components: {
    OwnerAdd
  },
  props: {
    groupId: 0
  },
  data: function () {
    return {
      users: [],
      cnt: 0
    }
  },
  methods: {
   getusers() {
      axios.get('/api/e_learning2/owner_list/' + this.groupId)
        .then((res) => {
          this.users = res.data
          this.cnt = this.users.length
        })
    },
    makeAdmin:function(dialog, id) {
      axios.delete('/api/e_learning2/owner_list/' + id)
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
          body: '削除します。よろしいですか？'
        }
      };
    },
    openModal: function(){
      this.$modal.show('modal_owner_add')
    },
    closeModal: function(){
      this.$modal.hide('modal_owner_add')
      this.getusers()
    },
  },
  mounted() {
    this.$store.commit('auth_e_learning2/setE_Groups_Id', this.groupId)
    this.getusers()
  }
}
</script>