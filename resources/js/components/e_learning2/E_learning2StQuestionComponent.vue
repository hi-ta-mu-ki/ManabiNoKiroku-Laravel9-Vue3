<template>
  <div class="container-fluid">
    <div class="p-1 mb-3 bg-primary text-white row">
      <div class="col-sm-3">
        <div class="row">
          <label for="selectclass" class="col-form-label col-sm-4 ms-2 me-2">クラスをえらぶ：</label>
          <div class="col-sm-6">
            <select class="form-select " id="selectclass" @change="jump1" v-model="e_classes_id">
              <option v-for="classes_menu in classes_menus" :key="classes_menu.id" v-bind:value="classes_menu.id" >{{ classes_menu.name }}</option>
            </select>
          </div>
        </div>
      </div>
      <div class="col-sm-2">
        <div v-if="isClassSelect">
          <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#answerlist_Modal">
            これまでのきろく
          </button>
          <div class="modal fade" id="answerlist_Modal" tabindex="-1" aria-labelledby="answerlist_ModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen">
              <div class="modal-content text-black">
                <div class="modal-header">
                  <h5 class="modal-title" id="answerlist_ModalLabel">これまでのきろく</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <table class="table table-hover table-sm" ref="table">
                    <thead class="thead-light">
                      <tr>
                        <th scope="col">セクションめい</th>
                        <th scope="col">かいとうじこく</th>
                        <th scope="col" v-for="i in n - 3" :key="i">
                          もんだい{{ i }}
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="answer in answer_lists" :key="answer.id">
                        <td v-for="i in n - 1" :key="i">
                          <div v-if="i < 3">
                            {{ answer[i] }}
                          </div>
                          <div v-else class="text-center">
                            <span v-if="answer[i]">
                              <img src="/image/smile2_small.png" border="0" />
                            </span>
                            <span v-else>
                              <img src="/image/smile3_small.png" border="0" />
                            </span>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-7">
        <div class="row">
          <label for="selectsection" class="col-form-label col-sm-2 me-2">セクションをえらぶ：</label>
          <div class="col-sm-9">
            <select class="form-select" id="selectsection" @change="jump2" v-model="no">
              <option v-for="question in questions_menu" :key="question.no" v-bind:value="question.no" >{{ question.quest }}</option>
            </select>
          </div>
        </div>
      </div>
    </div>
    <div class="row justify-content-center" v-if="no==null">
      <div class="col-sm-12 h1">
        <div class="text-center">うえのメニューからクラス，つぎにセクションをえらんでください。</div>
      </div>
    </div>
    <div class="row justify-content-center" v-else-if="!answered">
      <div class="col-sm-12">
        <h3>{{question_num}}もんちゅう{{correct}}もんせいかい
        <span v-if="questionIndex != 0">
          <span v-for="(smile, i) in corrects" :key="i">
            <span v-if="smile">
              <img src="/image/smile2.png" border="0">
            </span>
            <span v-else>
              <img src="/image/smile3.png" border="0">
            </span>
          </span>
        </span></h3>
        <h1><p class="badge bg-primary">だい {{ (questionIndex + 1) }} もん</p></h1>
        <br>
        <div class="text-primary">
          <h4><span v-html="$sanitize(currentQuestion.quest)"></span></h4>
        </div>
        <hr>
        <div class="container">
          <div class="mb-1"><button type="button" class="btn btn-primary btn-lg btn-block text-start mb-2"
            @click="addAnswer(1)"> 1 . <span v-html="$sanitize(currentQuestion.ans1)"></span></button></div>
          <div class="mb-1"><button type="button" class="btn btn-primary btn-lg btn-block text-start mb-2"
            @click="addAnswer(2)"> 2 . <span v-html="$sanitize(currentQuestion.ans2)"></span></button></div>
          <div class="mb-1"><button type="button" class="btn btn-primary btn-lg btn-block text-start mb-2"
            @click="addAnswer(3)"> 3 . <span v-html="$sanitize(currentQuestion.ans3)"></span></button></div>
          <div class="mb-1"><button type="button" class="btn btn-primary btn-lg btn-block text-start mb-2"
            @click="addAnswer(4)"> 4 . <span v-html="$sanitize(currentQuestion.ans4)"></span></button></div>
        </div>
      </div>
    </div>
    <div class="row justify-content-center" v-else-if="answered">
      <div class="col-sm-12">
        <h3>{{question_num}}もんちゅう{{correct}}もんせいかい
        <span v-for="(smile, i) in corrects" :key="i">
          <span v-if="smile">
            <img src="/image/smile2.png" border="0">
          </span>
          <span v-else>
            <img src="/image/smile3.png" border="0">
          </span>
        </span></h3>
        <h1><p class="badge bg-primary">だい {{ (questionIndex + 1) }} もん</p></h1>
        <br>
        <div class="text-primary">
          <h4><span v-html="$sanitize(currentQuestion.quest)"></span></h4>
        </div>
        <h4><table border = 0>
        <tr><td width = "5%" class="text-center"><span v-if="currentQuestion.ans == 1">〇</span><span v-else>×</span></td><td>　1 . <span v-html="$sanitize(currentQuestion.ans1)"></span></td></tr>
        <tr><td width = "5%" class="text-center"><span v-if="currentQuestion.ans == 2">〇</span><span v-else>×</span></td><td>　2 . <span v-html="$sanitize(currentQuestion.ans2)"></span></td></tr>
        <tr><td width = "5%" class="text-center"><span v-if="currentQuestion.ans == 3">〇</span><span v-else>×</span></td><td>　3 . <span v-html="$sanitize(currentQuestion.ans3)"></span></td></tr>
        <tr><td width = "5%" class="text-center"><span v-if="currentQuestion.ans == 4">〇</span><span v-else>×</span></td><td>　4 . <span v-html="$sanitize(currentQuestion.ans4)"></span></td></tr>
        </table></h4>
        <hr>
        <h4>
          <span v-if="currentQuestion.ans == answers[questionIndex]"><div class="text-primary ms-3">あなたのこたえは「{{answers[questionIndex]}}」　せいかい！！</div></span>
          <span v-else><div class="text-danger ms-3">あなたのこたえは「{{answers[questionIndex]}}」　まちがい。。。　せいかいは「{{ currentQuestion.ans }}」でした。</div></span><br>
          <h3><span class="badge bg-primary ms-3">かいせつ</span></h3>
          <span v-if="currentQuestion.exp1"><div class="ms-3">　1 . <span v-html="$sanitize(currentQuestion.exp1)"></span></div></span>
          <span v-if="currentQuestion.exp2"><div class="ms-3">　2 . <span v-html="$sanitize(currentQuestion.exp2)"></span></div></span>
          <span v-if="currentQuestion.exp3"><div class="ms-3">　3 . <span v-html="$sanitize(currentQuestion.exp3)"></span></div></span>
          <span v-if="currentQuestion.exp4"><div class="ms-3">　4 . <span v-html="$sanitize(currentQuestion.exp4)"></span></div></span>
          <span v-if="currentQuestion.exp0"><div class="ms-3"><span v-html="$sanitize(currentQuestion.exp0)"></span></div></span>
        </h4>
      </div>
      <div class="row justify-content-center" v-if="!completed">
        <span><button type="button" class="btn btn-primary btn-lg btn-block text-start"
          @click="nextQuestion"> つぎのもんだい </button></span>
      </div>
      <div class="row justify-content-center" v-else-if="completed">
        <div class="col-sm-12 h1">
          <div class="text-center">このセクションのもんだいは終わりました。うえのメニューからほかのセクションをえらんでください。</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
export default {
  props: {
    no: String,
  },
  data: function () {
    return {
      classes_menus: [],
      isClassSelect: false,
//      no: 0,
      answers: [],
      questionIndex: 0,
      questions_menu: [],
      questions: {},
      answered: false,
      correct: 0,
      corrects: [],
      question_num: 0,
      s_id: 0,
      e_classes_id: 0,
      user_id: 0,
      answer_lists: [],
      n: 0,

    }
  },
  methods: {
    getClassesMenu() {
      axios.get('/api/e_learning2/classes_menu')
        .then((res) => {
          this.classes_menus = res.data
        })
    },
    getQuestionsMenu() {
      axios.get('/api/e_learning2/st_menu/' + this.e_classes_id)
        .then((res) => {
          this.questions_menu = res.data
        })
    },
    getQuestions() {
      axios.get('/api/e_learning2/st/' + this.e_classes_id + '/' + this.no)
        .then((res) => {
          this.questions = res.data
          this.question_num = this.questions.length
        })
    },
    addAnswer: function(index) {
      this.answers.push(index)
      const formData = new FormData()
      formData.append('user_id', this.user_id)
      formData.append('e_classes_id', this.e_classes_id)
      formData.append('s_id', this.s_id)
      formData.append('no', this.no)
      formData.append('q_no', this.questionIndex + 1)
      formData.append('ans', index)
      let correct
      if(this.questions[this.questionIndex].ans == index){
        correct = 1
        this.correct++
      }
      else correct = 0
      this.corrects.push(correct)
      formData.append('correct', correct)
      axios.post('/api/e_learning2/st/answer', formData)
      return this.answered = true
    },
    nextQuestion: function() {
      if(!this.completed) {
        this.questionIndex++
        return this.answered = false
      }
    },
    jump1: function() {
      this.isClassSelect = true
      this.getQuestionsMenu()
      this.getAnswer_lists()
      if(this.no != null) this.getQuestions()
    },
    jump2: function() {
      this.questionIndex = 0
      this.answers.length = 0
      this.answered = false
      this.correct = 0
      this.corrects.splice(0);
      this.getQuestions()
      const date= new Date()
      this.s_id = date.getTime() / 1000
    },
    getAnswer_lists() {
      axios
        .get(
          "/api/e_learning2/st/answer/" + this.user_id + "/" + this.e_classes_id
        )
        .then((res) => {
          this.answer_lists = res.data;
          for (let i = 0; i < this.answer_lists.length; i++) {
            if (this.n < this.answer_lists[i].length)
              this.n = this.answer_lists[i].length;
          }
        });
    },
    async logout () {
      await this.$store.dispatch('auth_e_learning2/logout')
      if (this.apiStatus) {
        this.$router.push('/e_learning2/login')
      }
    },
  },
  computed: {
    currentQuestion: function() {
      return this.questions[this.questionIndex]
    },
    completed: function() {
      return (this.questions.length == this.answers.length)
    },
    ...mapState({
      apiStatus: state => state.auth_e_learning2.apiStatus
    })
  },
  mounted() {
    this.getClassesMenu()
    this.user_id = this.$store.getters['auth_e_learning2/id']
  }
}
</script>
