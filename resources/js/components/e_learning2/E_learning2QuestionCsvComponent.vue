<template>
  <div class="container-fluid">
    <h2 class="title">問題 CSV インポート</h2>
    <div class="row">
      <div class="col">
        <div v-if="errors">
          <ul v-if="errors.q_csv">
            <li v-for="msg in errors.q_csv" :key="msg">{{ msg }}</li>
          </ul>
        </div>
        <div class="image-input ms-1">
          <div class="image-input__field" :class="[{'-drag': isDrag == 'new'}]"
            @dragover.prevent="checkDrag($event, 'new', true)"
            @dragleave.prevent="checkDrag($event, 'new', false)"
            @drop.prevent="onDrop">
            <label>
              <input type="file" class="drop_input" @change="onDrop">
              <p>ここにファイルをドロップ，または，クリックしてファイルを選択すると<br>
              右にプレビューします。<br>
              よければアップロードを押してインポートします。<br>
              キャンセルはファイル選択画面からキャンセルをクリックします。</p>
            </label>
          </div>
        </div>
        <form class="form" enctype="multipart/form-data" @submit.prevent="submit">
          <div class="mt-1 ms-1" v-show="select1">
            <button type="submit" class="btn btn-success text-white">アップロード</button>
          </div>
        </form>
        <form class="form" enctype="multipart/form-data" @submit.prevent="submit2">
          <div class="mt-1 ms-1" v-show="select2">
            <button type="submit" class="btn btn-success text-white">アップロード</button>
            <div class="alert-danger ms-1">※問題が生じました。プレビューを確認してデータに問題がなければもう一度。</div>
          </div>
        </form>
        <div class="ms-1">{{ filename }}</div>
        <div v-show="!validate" class="alert-danger ms-1">{{ error_mes }}</div>
      </div>
    </div>
    <div class="mt-1 mb-1 ms-1">
      <table class="table table-bordered border-secondary table-sm">
        <tbody>
          <tr>
            <th scope="col">グループ番号</th>
            <th scope="col">セクション番号</th>
            <th scope="col">問題番号</th>
            <th scope="col">問題</th>
            <th scope="col">解答群1</th>
            <th scope="col">解答群2</th>
            <th scope="col">解答群3</th>
            <th scope="col">解答群</th>
            <th scope="col">正解</th>
            <th scope="col">解説1</th>
            <th scope="col">解説2</th>
            <th scope="col">解説3</th>
            <th scope="col">解説4</th>
            <th scope="col">解説</th>
          </tr>
          <tr v-for="(question, index) in questions" :key="index">
            <td v-for="(column, index) in question" :key="index" v-html="column"></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
  import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../../util'
  export default {
    data () {
      return {
        q_csv: null,
        select1: false,
        select2: false,
        validate: true,
        filename: null,
        errors: null,
        error_mes: null,
        isDrag: null,
        questions: []
      }
    },
  methods: {
    checkDrag(event, key, status) {
      if (status && event.dataTransfer.types != "text/plain") {
        return false
      }
      this.isDrag = status ? key : null
    },
    // フォームでファイルが選択されたら実行される
    onDrop (event) {
      this.isDrag = null //ドラッグ中のクラスを外しておく。
      this.select1 = true
      this.validate = true
      this.q_csv = event.target.files ? event.target.files : event.dataTransfer.files //ファイル取得
      // 何も選択されていなかったら処理中断
      if (this.q_csv.length === 0) {
        this.reset()
        return false
      }
      // ファイルがcsvではなかったら処理中断
      if (! this.q_csv[0].name.match(".csv")) {
        this.reset()
        return false
      }
      this.filename = this.q_csv[0].name
      let reader = new FileReader()
      reader.readAsText(this.q_csv[0], "Shift_JIS")
      reader.onload = () => {
        let lines = reader.result.split("\n")
        let linesArr = []
        for (let i = 1; i < lines.length - 1; i++) {
          linesArr[i] = lines[i].split(",")
        }
        for (let i = 1;  i < linesArr.length; i++) {
          if (Number.isInteger(linesArr[i][0]*1) == false) {
            linesArr[i][0] = '<div class="alert-danger">' + linesArr[i][0] + '</div>'
            this.validate = false
          }
          if (Number.isInteger(linesArr[i][1]*1) == false) {
            linesArr[i][1] = '<div class="alert-danger">' + linesArr[i][1] + '</div>'
            this.validate = false
          }
          if (Number.isInteger(linesArr[i][2]*1) == false) {
            linesArr[i][2] = '<div class="alert-danger">' + linesArr[i][7] + '</div>'
            this.validate = false
          }
          if (Number.isInteger(linesArr[i][8]*1) == false) {
            linesArr[i][8] = '<div class="alert-danger">' + linesArr[i][7] + '</div>'
            this.validate = false
          }        }
        if(this.validate == false) {
            this.select1 = false
            this.error_mes = "整数値であるべき項目に文字があります。一度ファイル選択をキャンセルし，データを修正してからやり直してください。"
        }
        this.questions = linesArr
      }
    },
    // 入力欄の値とプレビュー表示をクリアするメソッド
    reset () {
      this.questions = null
      this.q_csv = null
      this.filename = null
      this.$el.querySelector('input[type="file"]').value = null
      this.select1 = false
      this.select2 = false
      this.error_mes = null
      this.validate = true
    },
    async submit () {
      if(this.q_csv != null) {
        const formData = new FormData()
        formData.append('csvfile', this.q_csv[0])
        const response = await axios.post('/api/e_learning2/question/import', formData)
        if (response.status === UNPROCESSABLE_ENTITY) {
          this.errors = response.data.errors
          this.select1 = false
          this.select2 = true
          this.errors = null
          return false
        }
        if (response.status !== CREATED && response.status !== OK) {
          this.$store.commit('error/setCode', response.status)
          return false
        }
        this.reset()
        this.$emit('input', false)

        this.$router.push(`/e_learning2/tc/question`)
      }
    },
    async submit2 () {
      if(this.q_csv != null) {
        const formData = new FormData()
        formData.append('csvfile', this.q_csv[0])
        const response = await axios.post('/api/e_learning2/question/import2', formData)
        if (response.status === UNPROCESSABLE_ENTITY) {
          this.errors = response.data.errors
          return false
        }
        if (response.status !== CREATED && response.status !== OK) {
          this.$store.commit('error/setCode', response.status)
          return false
        }
        this.reset()
        this.$emit('input', false)
        this.$router.push(`/e_learning2/tc/question`)
      }
    }
  }
}
</script>