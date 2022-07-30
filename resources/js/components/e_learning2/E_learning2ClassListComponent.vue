<template>
  <div class="container">
    <h2 class="title">クラス一覧</h2>
    <div class="bg-primary mb-1">
      <div class="p-1 mb-1 bg-primary row">
        <router-link v-bind:to="{name: 'tc.classcreate', params: {groupId: groupId}}">
          <button class="btn btn-success text-white">クラスを追加</button>
        </router-link>
      </div>
    </div>
    <table class="table table-hover">
      <thead class="thead-light">
        <tr>
          <th scope="col">ID</th>
          <th scope="col">クラス名</th>
          <th scope="col">パスコード</th>
          <th scope="col"></th>
          <th scope="col"></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="item in items" :key="item.id">
          <th scope="row">{{ item.id }}</th>
          <td>{{ item.name }}</td>
          <td>{{ item.pass_code }}</td>
          <td>
            <router-link v-bind:to="{name: 'tc.classedit', params: {classId: item.id}}">
              <button class="btn btn-success text-white">編集</button>
            </router-link>
          </td>
          <td>
            <button class="btn btn-danger text-white" @click="class_delete(item.id)" data-bs-toggle="modal" data-bs-target="#classdelete_Modal">削除</button>
          </td>
        </tr>
      </tbody>
    </table>
    <div class="modal fade" id="classdelete_Modal" tabindex="-1" aria-labelledby="classdelete_ModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content text-black">
          <div class="modal-header">
            <h5 class="modal-title" id="classdelete_ModalLabel">クラス削除</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            所属する生徒，その生徒の成績も削除します。よろしいですか？
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
  props: {
    groupId: 0
  },
  data: function () {
    return {
      items: [],
      del_id: 0
    }
  },
  methods: {
    getClasses() {
      axios.get('/api/e_learning2/class_list/' + this.groupId)
        .then((res) => {
          this.items = res.data
        })
    },
    class_update() {
      this.getClasses()
    },
    class_delete:function(id) {
      this.del_id = id
    },
    delete_go() {
      axios.delete('/api/e_learning2/class/' + this.del_id)
      this.getClasses()
    },
  },
  mounted() {
    this.getClasses()
  }
}
</script>