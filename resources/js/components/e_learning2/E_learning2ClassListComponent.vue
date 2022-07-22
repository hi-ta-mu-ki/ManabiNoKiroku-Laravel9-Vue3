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
            <button class="btn btn-danger text-white" v-confirm="onAlert(item.id)">削除</button>
          </td>
        </tr>
      </tbody>
    </table>
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
    }
  },
  methods: {
    getclasses() {
      axios.get('/api/e_learning2/class_list/' + this.groupId)
        .then((res) => {
          this.items = res.data
        })
    },
    makeAdmin:function(dialog, id) {
      axios.delete('/api/e_learning2/class/' + id)
      this.getclasses()
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
            body: '所属する生徒，その生徒の成績も削除します。よろしいですか？'
          }
      };
    }
  },
  mounted() {
    this.getclasses()
  }
}
</script>