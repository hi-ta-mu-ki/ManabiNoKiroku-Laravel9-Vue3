<template>
  <div v-show="modelValue">
    <canvas id="chart"></canvas>
  </div>
</template>

<script>
import Chart from 'chart.js/auto';
export default {
  props: {
    no: 0,
    q_no: 0,
    modelValue: {
      type: Boolean,
      required: true
    }
  },
  data: function() {
    return {
      answer_data: []
    }
  },
  methods: {
    renderChart() {
      let ctx = document.getElementById("chart").getContext('2d');
      new Chart(ctx, {
        type: 'pie',
        data:{
          labels: ["解答1", "解答2", "解答3", "解答4"],
          datasets: [{
            label: '解答数',
            data: this.answer_data,
            backgroundColor: ["#ff0000", "#ffff00", "#0000ff", "#00ff00"], // データセットの円弧の塗りつぶし色
            borderColor: "transparent", // データセットの円弧の境界線の色データセットの円弧の塗りつぶし色
            borderWidth: 1, // データセットの円弧の境界線の太さ
          }]
        },
        options: {
          legend: {
            position: "bottom" // 凡例の位置
          },
        }
      });
    },
    getAnswers() {
      axios.get('/api/e_learning2/question/answer_g/'+ this.$store.getters['auth_e_learning2/e_groups_id'] +'/' + this.no + '/' + this.q_no)
        .then((res) => {
          this.answer_data = res.data
          this.renderChart();
        });
    }
  },
  watch: {
    modelValue: function(){
      this.getAnswers()
    }
  },
}
</script>
