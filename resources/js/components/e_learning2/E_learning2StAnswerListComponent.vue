<template>
  <modal name="modal_answer_list" :draggable="true" :resizable="true" :scrollable="true" width="90%" height="auto">
    <div id="overlay">
      <div id="content">
        <div class="container-fluid">
          <table class="table table-hover table-sm" ref="table">
            <thead class="thead-light">
            <tr>
              <th scope="col">セクションめい</th>
              <th scope="col">かいとうじこく</th>
              <th scope="col" v-for="i in n - 3" :key="i">もんだい{{ i }}</th>
            </tr>
            </thead>
            <tbody>
              <tr v-for="answer in getItems" :key="answer.id">
                <td v-for="i in n - 1" :key="i">
                  <div v-if="i < 3">
                    {{ answer[i] }}
                  </div>
                  <div v-else class="text-center">
                    <span v-if="answer[i]">
                      <img src="/image/smile2_small.png" border="0">
                    </span>
                    <span v-else>
                      <img src="/image/smile3_small.png" border="0">
                    </span>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
          <div>
            <vuejs-paginate
              :page-count="getPaginateCount"
              :prev-text="'<'"
              :next-text="'>'"
              :click-handler="paginateClickCallback"
              :container-class="'pagination justify-content-center'"
              :page-class="'page-item'"
              :page-link-class="'page-link'"
              :prev-class="'page-item'"
              :prev-link-class="'page-link'"
              :next-class="'page-item'"
              :next-link-class="'page-link'"
              :first-last-button="true"
              :first-button-text="'<<'"
              :last-button-text="'>>'"
            ></vuejs-paginate>
          </div>
          <button class="btn btn-primary" @click="clickEvent">Close</button>
        </div>
      </div>
    </div>
  </modal>
</template>

<script>
import VueJsPaginate from "vuejs-paginate";
export default {
  components: {
    "vuejs-paginate": VueJsPaginate,
  },
  name: 'AnswerList2',
  props: {
    e_classes_id: 0,
    user_id: 0
  },
  data: function () {
    return {
      answers: [],
      items: [],
      n: 0,
      currentPage: 1,
      perPage: 10,
    }
  },
  methods: {
    getAnswers() {
      axios.get('/api/e_learning2/st/answer/' + this.user_id + '/' + this.e_classes_id)
        .then((res) => {
          this.answers = res.data
          for(let i = 0; i < this.answers.length; i++) {
            if(this.n < this.answers[i].length)
              this.n = this.answers[i].length;
          }
        });
    },
    clickEvent: function() {
      this.$emit('from-child')
    },
    paginateClickCallback: function (pageNum) {
      this.currentPage = Number(pageNum)
    },
  },
  computed: {
    getItems: function () {
      let start = (this.currentPage - 1) * this.perPage
      let end = this.currentPage * this.perPage
      this.items = this.answers.slice(start, end)
      this.n = 0
      for(let i = 0; i < this.items.length; i++){
        if(this.n < this.items[i].length)
          this.n = this.items[i].length
      }
      return this.items
    },
    getPaginateCount: function () {
      return Math.ceil(this.answers.length / this.perPage)
    },
  },
  mounted() {
    this.getAnswers()
  }
}
</script>