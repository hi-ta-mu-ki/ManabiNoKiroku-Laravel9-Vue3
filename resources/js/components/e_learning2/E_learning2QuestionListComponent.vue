<template>
  <div class="container-fluid">
    <h2 class="title">問題一覧</h2>
    <div class="bg-primary mb-1">
      <div class="p-1 mb-1 bg-primary text-white form-inline row">
        <div class="col-sm-3">
          <div class="row">
            <label for="selectgroup" class="col-form-label col-sm-4 me-2 text-end">グループを選択：</label>
            <div class="col-sm-6">
              <select class="form-select" id="selectgroup" @change="jump1" v-model="e_groups_id">
                <option v-for="groups_menu in groups_menus" :key="groups_menu.id" v-bind:value="groups_menu.id" >{{ groups_menu.name }}</option>
              </select>
            </div>
          </div>
        </div>
        <div class="col-sm-9">
          <div class="row">
            <label for="selectsection" class="col-form-label col-sm-3 me-2 text-end">セクションを選択：</label>
            <div class="col-sm-8">
              <select class="form-select" id="selectsection" @change="jump2" v-model="no">
                <option v-for="question in questions_menu" :key="question.no" v-bind:value="question.no" >{{ question.quest }}</option>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div v-if="isGroupSelect">
      <table class="table table-hover">
        <thead class="thead-light">
        <tr>
          <th scope="col" width="3%">ID</th>
          <th scope="col" width="5%">セクション番号</th>
          <th scope="col" width="3%">問題番号</th>
          <th scope="col" width="74%">問題</th>
          <th scope="col" width="7%"></th>
          <th scope="col" width="4%"></th>
          <th scope="col" width="4%"></th>
        </tr>
        </thead>
        <tbody>
          <tr v-for="question in questions" :key="question.id">
            <th scope="row">{{ question.id }}</th>
            <td>{{ question.no }}</td>
            <td>{{ question.q_no }}</td>
            <td>
              <div v-if="question.q_no == 0">
                <strong>【セクションタイトル】<p v-html="$sanitize(question.quest)"></p></strong>
              </div>
              <div v-else>
                <p v-html="$sanitize(question.quest)"></p>
              </div>
            </td>
            <td>
              <div v-if="question.q_no != 0">
                <router-link v-bind:to="{name: 'tc.questionshow', params: {questionId: question.id, no: question.no, q_no: question.q_no}}">
                  <button class="btn btn-primary">プレビュー</button>
                </router-link>
              </div>
              <div v-else>
                <router-link v-bind:to="{name: 'tc.answer', params: {no: question.no }}">
                  <button class="btn btn-warning">生徒の成績</button>
                </router-link>
              </div>
            </td>
            <td>
              <router-link v-bind:to="{name: 'tc.questionedit', params: {questionId: question.id }}">
                <button class="btn btn-success text-white">編集</button>
              </router-link>
            </td>
            <td>
              <button class="btn btn-danger text-white" @click="question_delete(question.id)" data-bs-toggle="modal" data-bs-target="#questiondelete_Modal">削除</button>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="modal fade" id="questiondelete_Modal" tabindex="-1" aria-labelledby="questiondelete_ModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content text-black">
            <div class="modal-header">
              <h5 class="modal-title" id="questiondelete_ModalLabel">問題削除</h5>
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
  </div>
</template>

<script>
export default {
  data: function () {
    return {
      groups_menus: [],
      e_groups_id: 0,
      isGroupSelect: false,
      no: '',
      questions_menu: [],
      questions: []
    }
  },
  methods: {
    getGroupsMenu() {
      axios.get('/api/e_learning2/groups_menu')
        .then((res) => {
          this.groups_menus = res.data
        })
    },
    jump1: function() {
      this.$store.commit('auth_e_learning2/setE_Groups_Id', this.e_groups_id)
      this.isGroupSelect = true
      this.getQuestionsMenu()
    },
    getQuestionsMenu() {
      axios.get('/api/e_learning2/section_menu1/' + this.$store.getters['auth_e_learning2/e_groups_id'])
        .then((res) => {
          this.questions_menu = res.data
        });
    },
    getQuestions() {
      axios.get('/api/e_learning2/question/'+ this.$store.getters['auth_e_learning2/e_groups_id'] +'/' + this.no)
        .then((res) => {
          this.questions = res.data
        });
    },
    question_update() {
      this.getQuestions()
    },
    question_delete:function(user_id) {
      this.del_id = id
    },
    delete_go() {
      axios.delete('/api/e_learning2/question/' + this.del_id)
      this.getQuestions()
      // dialog.close()
    },
    // question_delete(id) {
    //   axios.delete('/api/e_learning2/question/' + id)
    //     .then((res) => {
    //       this.getQuestions()
    //     });
    // },
    jump2: function() {
      this.getQuestions()
    },
  },
  mounted() {
    this.getGroupsMenu()
  }
}
</script>