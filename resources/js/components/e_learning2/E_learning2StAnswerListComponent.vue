<template>
  <div v-show="modelValue">
    <div class="container-fluid">
      <table class="table table-hover table-sm" ref="table">
        <thead class="thead-light">
        <tr>
          <th scope="col">セクションめい</th>
          <th scope="col">かいとうじこく</th>
          <th scope="col" v-for="i in num" :key="i">もんだい{{ i }}</th>
        </tr>
        </thead>
        <tbody>
          <tr v-for="answer in items" :key="answer.id">
            <td v-for="i in n - 1" :key="i">
              <div v-if="i < 3">
                {{ answer[i] }}
              </div>
              <div v-else class="text-center">
                <span v-if="answer[i]">
                  <img src="/image/smile2_small.png" border="0">
                </span>
                <span v-else>
                  <span v-if="answer[i] == 0">
                    <img src="/image/smile3_small.png" border="0">
                  </span>
                </span>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="row">
        <div class="col-sm-6">
          <ul class="pagination">
            <li :class="{ disabled: current_page <= 1 }">
              <a class="page-link" href="#" @click="change(1)">&laquo;</a>
            </li>
            <li :class="{ disabled: current_page <= 1 }">
              <a class="page-link" href="#" @click="change(current_page - 1)">&lt;</a>
            </li>
            <li
              v-for="page in pages"
              :key="page"
              :class="{ active: page === current_page }"
            >
              <a class="page-link" href="#" @click="change(page)">{{ page }}</a>
            </li>
            <li :class="{ disabled: current_page >= last_page }">
              <a class="page-link" href="#" @click="change(current_page + 1)">&gt;</a>
            </li>
            <li :class="{ disabled: current_page >= last_page }">
              <a class="page-link" href="#" @click="change(last_page)">&raquo;</a>
            </li>
          </ul>
        </div>
        <div style="margin-top: 40px" class="col-sm-6 text-right">
          全 {{ total }} 件中 {{ from }} 〜 {{ to }} 件表示
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    e_classes_id: 0,
    user_id: 0,
    modelValue: {
      type: Boolean,
      required: true
    }
  },
  data: function () {
    return {
      answer_lists: [],
      n: 0,
      num: 0,
      items: [],
      perpage: 5,
      current_page: 1,
      last_page: 1,
      total: 1,
      from: 1,
      to: 5,
      page :1
    }
  },
  methods: {
    getAnswers() {
      axios.get('/api/e_learning2/st/answer/' + this.user_id + '/' + this.e_classes_id)
        .then((res) => {
          this.answer_lists = res.data
          this.total = this.answer_lists.length
          this.last_page = Math.floor((this.total - 1) / this.perpage + 1)
          this.change(1)
        });
    },
    change(page) {
      if (page >= 1 && page <= this.last_page){
        this.current_page = page
        let start = (this.current_page - 1) * this.perpage
        let end = this.current_page * this.perpage
        this.items = this.answer_lists.slice(start, end)
        this.from = start + 1
        if (end > this.total) this.to = this.total
        else this.to = end
        this.n = 0
        for(let i = 0; i < this.items.length; i++){
          if(this.n < this.items[i].length)
            this.n = this.items[i].length
        }
        this.num = this.n - 3
      }
    },
  },
  watch: {
    modelValue: function(){
      this.getAnswers()
    }
  },
  computed: {
    pages() {
      let start = _.max([this.current_page - 2, 1])
      let end = _.min([start + 5, this.last_page + 1])
      start = _.max([end - 5, 1])
      return _.range(start, end)
    },
  }
}
</script>