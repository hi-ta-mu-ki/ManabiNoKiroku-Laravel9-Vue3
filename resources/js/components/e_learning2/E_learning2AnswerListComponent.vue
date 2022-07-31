<template>
  <div v-show="modelValue">
    <div class="container-fluid">
      <h3>{{ section_name }}</h3>
      <table class="table table-hover table-sm" ref="table">
        <thead class="thead-light">
        <tr>
          <th scope="col">名前</th>
          <th scope="col">メールアドレス</th>
          <th scope="col">解答時刻</th>
          <th scope="col" v-for="i in Number(n) - 1" :key="i">問題{{ i }}</th>
        </tr>
        </thead>
        <tbody>
          <tr v-for="answer in answers" :key="answer.id">
            <td v-for="i in Number(n) + 2" :key="i">{{ answer[i] }}</td>
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
  props: {
    no: '',
    n: 0,
    section_name: '',
    modelValue: {
      type: Boolean,
      required: true
    }
  },
  data: function () {
    return {
      answers: [],
    }
  },
  methods: {
    getAnswers() {
      axios.get('/api/e_learning2/question/answer/'+ this.$store.getters['auth_e_learning2/e_groups_id'] +'/' + this.no)
        .then((res) => {
          this.answers = res.data
        });
    },
    downloadExcelFile() {
      const data = this.$refs.table
      const wb = XLSX.utils.table_to_book(data)
      XLSX.writeFile(wb,'e_learning2_answer_list.xlsx')
    },
  },
  watch: {
    modelValue: function(){
      this.getAnswers()
    }
  },
}
</script>