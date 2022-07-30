<template>
  <div class="container-fluid">
    <div class="row justify-content-center" v-if="!answered">
      <div class="col-sm-12">
        <h3>げんざい，〇もんちゅう〇もんせいかい</h3>
        <h1><p class="badge bg-primary">だい {{ currentQuestion.q_no }} もん</p></h1>
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
        <h3>〇もんちゅう〇もんせいかい</h3>
        <h1><p class="badge bg-primary">だい {{ currentQuestion.q_no }} もん</p></h1>
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
          <span v-if="currentQuestion.ans == answers[0]"><div class="text-primary ms-3">あなたのこたえは「{{answers[0]}}」　せいかい！！</div></span>
          <span v-else><div class="text-danger ms-3">あなたのこたえは「{{answers[0]}}」　まちがい。。。　せいかいは「{{ currentQuestion.ans }}」でした。</div></span><br>
          <h3><span class="badge bg-primary ms-3">かいせつ</span></h3>
          <span v-if="currentQuestion.exp1"><div class="ms-3">　1 . <span v-html="$sanitize(currentQuestion.exp1)"></span></div></span>
          <span v-if="currentQuestion.exp2"><div class="ms-3">　2 . <span v-html="$sanitize(currentQuestion.exp2)"></span></div></span>
          <span v-if="currentQuestion.exp3"><div class="ms-3">　3 . <span v-html="$sanitize(currentQuestion.exp3)"></span></div></span>
          <span v-if="currentQuestion.exp4"><div class="ms-3">　4 . <span v-html="$sanitize(currentQuestion.exp4)"></span></div></span>
          <span v-if="currentQuestion.exp0"><div class="ms-3"><span v-html="$sanitize(currentQuestion.exp0)"></span></div></span>
        </h4>
      </div>
    </div>
    <hr>
    <div class="text-center mt-5">
      <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#answerlist_Modal">
        解答データ（グラフ）
      </button>
      <div class="modal fade" id="answerlist_Modal" tabindex="-1" aria-labelledby="answerlist_ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
          <div class="modal-content text-black">
            <div class="modal-header">
              <h5 class="modal-title" id="answerlist_ModalLabel">解答データ（グラフ）</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <AnswerGraph :no="no" :q_no="q_no" />
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import AnswerGraph from './E_learning2AnswerGraphComponent.vue'
export default {
  props: {
    questionId: 0,
    no: 0,
    q_no: 0
  },
  components: {
    AnswerGraph
  },
  data: function () {
    return {
      answers: [],
      questions: [],
      answered: false,
      correct: 0,
    }
  },
  methods: {
    getQuestions() {
      axios.get('/api/e_learning2/question/' + this.questionId)
        .then((res) => {
          this.questions = res.data
        })
    },
    addAnswer: function(index) {
      this.answers.push(index)
      return this.answered = true
    },
  },
  computed: {
    currentQuestion: function() {
      return this.questions
    },
  },
  mounted() {
    this.getQuestions()
  }
}
</script>
