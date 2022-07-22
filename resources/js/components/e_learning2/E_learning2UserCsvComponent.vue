<template>
  <div class="container-fluid">
    <h2 class="title">ユーザ CSV インポート</h2>
    <div class="row">
      <div class="col">
          <div>
            <div v-if="errors">
              <ul v-if="errors.u_csv">
                <li v-for="msg in errors.u_csv" :key="msg">{{ msg }}</li>
              </ul>
            </div>
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
            <div class="ms-1">{{ filename }}</div>
          </div>
        </form>
        <form class="form" enctype="multipart/form-data" @submit.prevent="submit2">
          <div class="mt-1 ms-1" v-show="select2">
            <button type="submit" class="btn btn-success text-white">アップロード</button>
            <div class="ms-1">{{ filename }}</div>
            ※問題が生じました。プレビューを確認してデータに問題がなければもう一度。
          </div>
        </form>
      </div>
    </div>
    <div class="mt-1 mb-1 ms-1">
      <table class="table table-bordered border-secondary table-sm">
        <tbody>
          <tr>
            <th scope="col">名前</th>
            <th scope="col">メールアドレス</th>
            <th scope="col">パスワード</th>
            <th scope="col">役割</th>
          </tr>
          <tr v-for="(user, index) in users" :key="index">
            <td v-for="(column, index) in user" :key="index">{{ column }}</td>
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
      u_csv: null,
      select1: false,
      select2: false,
      filename: null,
      errors: null,
      isDrag: null,
      users: []
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
      this.isDrag = null; //ドラッグ中のクラスを外しておく。
      this.select1 = true;
      this.u_csv = event.target.files ? event.target.files : event.dataTransfer.files; //ファイル取得
      // 何も選択されていなかったら処理中断
      if (this.u_csv.length === 0) {
        this.reset()
        return false
      }
      // ファイルがcsvではなかったら処理中断
      if (! this.u_csv[0].name.match(".csv")) {
        this.reset()
        return false
      }
      this.filename = this.u_csv[0].name
      let reader = new FileReader();
      reader.readAsText(this.u_csv[0], "Shift_JIS");
      reader.onload = () => {
        let lines = reader.result.split("\n")
        let linesArr = []
        for (let i = 1; i < lines.length; i++) {
          linesArr[i] = lines[i].split(",")
        }
        this.users = linesArr
      }
    },
    // 入力欄の値とプレビュー表示をクリアするメソッド
    reset () {
      this.users = null
      this.u_csv = null
      this.filename = null
      this.$el.querySelector('input[type="file"]').value = null
      this.select1 = false
      this.select2 = false
    },
    async submit () {
      if(this.u_csv != null) {
        const formData = new FormData()
        formData.append('csvfile', this.u_csv[0])
        const response = await axios.post('/api/e_learning2/ad/import', formData)
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
        this.$router.push({name: 'ad.userlist'})
      }
    },
    async submit2 () {
      if(this.u_csv != null) {
        const formData = new FormData()
        formData.append('csvfile', this.u_csv[0])
        const response = await axios.post('/api/e_learning2/ad/import2', formData)
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
        this.$router.push({name: 'ad.userlist'})
      }
    }
  }
}
</script>