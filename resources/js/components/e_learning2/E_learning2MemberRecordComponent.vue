<template>
  <div class="container-fluid">
    <h2 class="title">生徒個人記録</h2>
    <div class="bg-primary mb-1">
      <div class="p-1 mb-1 bg-primary text-white form-inline row">
        <div class="col-sm-4">
          <div class="row">
            <label for="selectclass" class="col-form-label col-sm-4 me-2 text-end">クラスを選択：</label>
            <div class="col-sm-6">
              <select class="form-select" id="selectclass" @change="jump1" v-model="e_classes_id">
                <option v-for="classes_menu in classes_menus" :key="classes_menu.id" v-bind:value="classes_menu.id" >{{ classes_menu.name }}</option>
              </select>
            </div>
          </div>
        </div>
        <div class="col-sm-8">
          <div v-if="isClassSelect" class="row">
            <label for="selectuser" class="col-form-label col-sm-2 me-2 text-end">生徒を選択：</label>
            <div class="col-sm-3">
              <select class="form-select" id="selectuser" @change="jump2" v-model="st_id">
                <option v-for="user in users" :key="user.user_id" v-bind:value="user.user_id" >{{ user.name }}</option>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div v-if="isUserSelect">
      <table class="table table-hover table-sm" ref="table">
        <thead class="thead-light">
        <tr>
          <th scope="col">セクション名</th>
          <th scope="col">解答時刻</th>
          <th scope="col" v-for="i in n - 3" :key="i">問題{{ i }}</th>
        </tr>
        </thead>
        <tbody>
          <tr v-for="answer in answers" :key="answer.id">
            <td v-for="i in n - 1" :key="i">{{ answer[i] }}</td>
          </tr>
        </tbody>
      </table>
      <button class="btn btn-success text-white" @click="downloadExcelFile()">Excelにダウンロード</button>
    </div>
  </div>
</template>

<script>
import XLSX from 'xlsx'
export default {
  data: function () {
    return {
      classes_menus: [],
      isClassSelect: false,
      users: [],
      e_classes_id: 0,
      answers: [],
      n: 0,
      st_id: 0,
      isUserSelect: false,
      user_id: 0
    }
  },
  methods: {
    getClassesMenu() {
      axios.get('/api/e_learning2/classes_menu/' + this.user_id)
        .then((res) => {
          this.classes_menus = res.data
        })
    },
    getUsers() {
      axios.get('/api/e_learning2/member_list_menu/' + this.e_classes_id)
        .then((res) => {
          this.users = res.data
        })
    },
    getAnswers() {
      axios.get('/api/e_learning2/st/answer/'+ this.st_id + '/' + this.$store.getters['auth_e_learning2/e_classes_id'])
        .then((res) => {
          this.answers = res.data
          for(let i = 0; i < this.answers.length; i++) {
            if(this.n < this.answers[i].length)
              this.n = this.answers[i].length
          }
        });
    },
    downloadExcelFile() {
      const data = this.$refs.table
      const wb = XLSX.utils.table_to_book(data)
      XLSX.writeFile(wb,'e_learning2_answer_list.xlsx')
    },
    jump1: function() {
      this.$store.commit('auth_e_learning2/setE_Classes_Id', this.e_classes_id)
      this.isClassSelect = true
      this.getUsers()
    },
    jump2: function() {
      this.isUserSelect = true
      this.getAnswers()
    },
  },
  mounted() {
    this.user_id = this.$store.getters['auth_e_learning2/id']
    this.getClassesMenu()
  }
}
</script>