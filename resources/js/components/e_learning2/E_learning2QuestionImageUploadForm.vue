<template>
  <div v-show="modelValue">
    <div v-show="loading">
      <Loader>Sending your photo...</Loader>
    </div>
    <div class="row">
      <form v-show="! loading" class="form" enctype="multipart/form-data" @submit.prevent="submit">
        <div class="ms-3>">
          <div v-if="errors">
            <ul v-if="errors.photo">
              <li v-for="msg in errors.photo" :key="msg">{{ msg }}</li>
            </ul>
          </div>
        </div>
        <div class="image-input ms-3">
          <div class="image-input__field" :class="[{'-drag': isDrag == 'new'}]"
            @dragover.prevent="checkDrag($event, 'new', true)"
            @dragleave.prevent="checkDrag($event, 'new', false)"
            @drop.prevent="onDrop">
            <label>
              <input type="file" class="drop_input" @change="onDrop">
              <p>ここにファイルをドロップ，または，クリックしてファイルを選択すると<br>
              右にプレビューします。<br>
              よければsubmitを押してアップロードします。<br>
              キャンセルはファイル選択画面からキャンセルをクリックします。</p>
            </label>
          </div>
        </div>
        <div class="mt-1 ms-3" v-show="select">
          <button type="submit" class="btn btn-success text-white">アップロード</button>
          <div class="ms-1">{{ filename }}</div>
        </div>
      </form>
      <div class="ms-3">
        <output v-if="preview1">
          <img :src="preview1" height=200>
        </output>
        <output v-if="preview2">
          <img :src="preview2" height=200>
        </output>
      </div>
    </div>
  </div>
</template>

<script>
import { CREATED, UNPROCESSABLE_ENTITY } from '../../util'
import Loader from './Loader.vue'
export default {
  components: {
    Loader
  },
  props: {
    modelValue: {
      type: Boolean,
      required: true
    }
  },
  data () {
    return {
      loading: false,
      select: false,
      preview1: null,
      preview2: null,
      photo: null,
      filename: null,
      errors: null,
      isDrag: null
    }
  },
  methods: {
    checkDrag(event, key, status) {
      if (status && event.dataTransfer.types == "text/plain") {
        //ファイルではなく、html要素をドラッグしてきた時は処理を中止
        return false
      }
      this.isDrag = status ? key : null
    },

    //inputタグとドラッグ&ドロップから呼ばれる
    onDrop (event) {
      this.isDrag = null //ドラッグ中のクラスを外しておく。
      this.select = true
      this.photo = event.target.files ? event.target.files : event.dataTransfer.files //ファイル取得
      console.log(this.photo)
      // 何も選択されていなかったら処理中断
      if (this.photo.length !== 1) {
        this.reset()
        return false
      }
      // ファイルが画像ではなかったら処理中断
      if (! this.photo[0].type.match('image.*')) {
        this.reset()
        return false
      }
      // FileReaderクラスのインスタンスを取得
      const reader = new FileReader()
      // ファイルを読み込み終わったタイミングで実行する処理
      reader.onload = e => {
        // previewに読み込み結果（データURL）を代入する
        // previewに値が入ると<output>につけたv-ifがtrueと判定される
        // また<output>内部の<img>のsrc属性はpreviewの値を参照しているので
        // 結果として画像が表示される
        this.preview1 = e.target.result
      }
      // ファイルを読み込む
      // 読み込まれたファイルはデータURL形式で受け取れる（上記onload参照）
      reader.readAsDataURL(this.photo[0])
      const image = new Image()
      image.src = e => {
        this.preview2 = e.target.result
      }
      this.filename = this.photo[0].name
    },
    // 入力欄の値とプレビュー表示をクリアするメソッド
    reset () {
      this.preview1 = ''
      this.preview2 = ''
      this.photo = null
      this.filename = null
      this.$el.querySelector('input[type="file"]').value = null
      this.errors.photo = null
      this.select = false
    },
    async submit () {
      if(this.photo != null){
        this.loading = true
        const formData = new FormData()
        formData.append('photo', this.photo[0])
        const response = await axios.post('/api/e_learning2/question/upload', formData)
        this.loading = false
        if (response.status === UNPROCESSABLE_ENTITY) {
          this.errors = response.data.errors
          return false
        }
        this.reset()
        this.$emit('input', false)
        if (response.status !== CREATED) {
          this.$store.commit('error/setCode', response.status)
          return false
        }
      }
    }
  }
}
</script>
<style>
  .image-input {
    background-color: #eee;
    width: 500px;
    height: 120px;
  }
  .image-input__field {
    width: 100%;
    height: 100%;
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  .image-input__field.over {
    background-color: #666;
  }
  .image-input__field > p {
    color: #aaa;
    text-align: center;
  }
  input.drop_input[type="file"] {
    display: none;
  }
</style>