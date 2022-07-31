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
          <tr v-for="answer in answer_lists" :key="answer.id">
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
      num: 0
    }
  },
  methods: {
    getAnswers() {
      axios.get('/api/e_learning2/st/answer/' + this.user_id + '/' + this.e_classes_id)
        .then((res) => {
          this.answer_lists = res.data
          for(let i = 0; i < this.answer_lists.length; i++) {
            if(this.n < this.answer_lists[i].length)
              this.n = this.answer_lists[i].length;
          }
          this.num = this.n - 3
        });
    },
  },
  watch: {
    modelValue: function(){
      this.getAnswers()
    }
  },
}
</script>