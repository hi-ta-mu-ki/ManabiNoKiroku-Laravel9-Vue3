<template>
  <div class="container">
    <h2 class="title">グループ編集</h2>
    <div class="row justify-content-center">
      <div class="col-sm-8">
        <form @submit.prevent="submit">
          <div class="form-group row">
            <label for="name" class="col-sm-2 col-form-label">グループ名</label>
            <input type="text" class="col-sm-6 form-control" id="name" v-model="group.name">
          </div>
          <div v-if="isMsg"><div class="alert alert-danger" role="alert">{{ msg }}</div></div>
          <div class="mt-4">
            <button type="submit" class="btn btn-primary">決定</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    e_groups_id: 0
  },
  data: function () {
    return {
      group: {},
      msg: '',
      isMsg: false
    }
  },
  methods: {
    getGroup() {
      axios.get('/api/e_learning2/group/' + this.e_groups_id)
        .then((res) => {
          this.group = res.data
        })
    },
    submit() {
      axios.put('/api/e_learning2/group/' + this.e_groups_id, this.group)
        .then((res) => {
          if(res.status== 200)
            this.$router.push({name: 'tc.grouplist'})
          else{
            this.isMsg = true
            this.msg = 'すでに使用されているグループ名です'
          }
        })
    }
  },
  mounted() {
    this.getGroup()
  }
}
</script>