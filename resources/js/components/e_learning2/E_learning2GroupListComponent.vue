<template>
  <div class="container">
    <h2 class="title">グループ一覧</h2>
    <div class="bg-primary mb-1">
      <div class="p-1 mb-1 bg-primary row">
        <router-link v-bind:to="{name: 'tc.groupcreate'}">
          <button class="btn btn-success text-white">グループを追加</button>
        </router-link>
      </div>
    </div>
    <table class="table table-hover">
      <thead class="thead-light">
        <tr>
          <th scope="col">ID</th>
          <th scope="col">グループ名</th>
          <th scope="col"></th>
          <th scope="col"></th>
          <th scope="col"></th>
          <th scope="col"></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="group in groups" :key="group.id">
          <th scope="row">{{ group.id }}</th>
          <td>{{ group.name }}</td>
          <td>
            <router-link v-bind:to="{name: 'tc.groupedit', params: {e_groups_id: group.id }}">
              <button class="btn btn-success text-white">編集</button>
            </router-link>
          </td>
          <td>
            <button class="btn btn-danger text-white" @click="group_delete(group.id)" data-bs-toggle="modal" data-bs-target="#groupdelete_Modal">削除</button>
          </td>
          <td>
            <router-link v-bind:to="{name: 'tc.owner', params: {e_groups_id: group.id }}">
              <button class="btn btn-success text-white">所有者一覧</button>
            </router-link>
          </td>
          <td>
            <router-link v-bind:to="{name: 'tc.classlist', params: {e_groups_id: group.id, userId: user_id }}">
              <button class="btn btn-success text-white">クラスを設定</button>
            </router-link>
          </td>
        </tr>
      </tbody>
    </table>
    <div class="modal fade" id="groupdelete_Modal" tabindex="-1" aria-labelledby="groupdelete_ModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content text-black">
          <div class="modal-header">
            <h5 class="modal-title" id="groupdelete_ModalLabel">グループ削除</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            このグループの問題，クラス，所属する生徒，成績のすべてを削除します。よろしいですか？
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
      groups: [],
      del_id: 0,
      user_id: 0
    }
  },
  methods: {
    getGroups() {
      axios.get('/api/e_learning2/group_list/' + this.user_id)
        .then((res) => {
          this.groups = res.data
        })
    },
    group_update() {
      this.getGroups()
    },
    group_delete:function(id) {
      this.del_id = id
    },
    delete_go() {
      axios.delete('/api/e_learning2/group/' + this.del_id)
      this.getGroups()
    },
  },
  mounted() {
    this.user_id = this.$store.getters['auth_e_learning2/id']
    this.getGroups()
  }
}
</script>