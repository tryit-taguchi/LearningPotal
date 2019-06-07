<template><!-- 「全国平均」なしの棒グラフ／タイトルあり -->
  <div :style="{width:width+'px',height:height+'px',color:'#000',backgroundColor:'#fff',margin:'10px auto 0'}">
    <div class="chart-outer">
      <div class="chart-header-outer">
        ヘッダー
      </div>
      <div class="chart-yaxis-outer">
        <div class="chart-yaxis">
          <span v-for="category in xaxis.categories">{{category}}</span>
        </div>
      </div>
      <div class="chart-series-outer">
        <svg class="chart-series" ref="series">
          <rect v-for="i in series[0].length" :x="barStrokeWidth/2" :y="(seriesHeight/series[0].length)*(i-1)+(barStrokeWidth/2)+(seriesHeight/series[0].length*0.1)" width="0" :height="barHeight" :stroke-width="barStrokeWidth" :stroke="getBarStrokeColor(i-1)" :fill="getBarFillColor(i-1)" />
        </svg>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    chartData: {},
    width: {},
    height: {},
    showYourSelect: Boolean,
    chartIndex: {},
    series: Array,
    xaxis: Object
  },
  data(){
    return{
      seriesWidth: 0,
      seriesHeight: 0,
      seriesMaxValue: []
    }
  },
  mounted() {
    this.seriesWidth = this.$refs.series.width.baseVal.value;
    this.seriesHeight = this.$refs.series.height.baseVal.value;
    this.series.forEach((v)=>{
      this.seriesMaxValue.push(Math.max(...v))
    })
    this.setBarAnimation()
  },
  computed: {
    barStrokeWidth: function(){
      return 2
    },
    barHeight: function(){
      const h = (this.seriesHeight/this.xaxis.categories.length)-(this.barStrokeWidth)-(this.seriesHeight/this.xaxis.categories.length*0.2)
      if(h>0){
        return h
      }else{
        return 0.1
      }
    },
    barStrokeColor: function(){
      return 'rgba(255,206, 86,1)'
    },
    barFillColor: function(){
      return 'rgba(255,206, 86,0.2)'
    }
  },
  watch: {
    series: function(){
      this.setBarAnimation()
    }
  },
  methods: {
    setBarAnimation(){
      this.$anime({
        targets: '.chart-series rect',
        width: (el,i)=>{
          return ( this.seriesWidth - this.barStrokeWidth ) * this.series[0][i] / this.seriesMaxValue[0];
        },
        round: 1,
        duration: 1000,
        delay: 500
      })
    },
    getBarStrokeColor(i){
      if(this.chartData.selectedNoList.includes(i)){
        return 'rgba( 86,206,255,1)'
      }else{
        return 'rgba(255,206, 86,1)'
      }
    },
    getBarFillColor(i){
      if(this.chartData.selectedNoList.includes(i)){
        return 'rgba(86, 206,255,0.2)'
      }else{
        return 'rgba(255,206, 86,0.2)'
      }
    }
  }
}
</script>

<style lang="scss">
.chart-outer{
  width: 100%;
  height: 100%;
  background-color: #ccc;
  display: grid;
  grid-template-columns: max-content 1fr;
  grid-template-rows: max-content 1fr;
  grid-template-areas: "header header" "yaxis series";
  grid-gap: 10px;
  padding: 10px;
}
.chart-header-outer{
  grid-area: header;
}
.chart-yaxis-outer{
  grid-area: yaxis;
  overflow: hidden;
}
.chart-yaxis{
  display: grid;
  grid-template-columns: 1fr;
  grid-template-rows: 1fr 1fr 1fr 1fr;
  height: 100%;
  span{
    // border: 1px solid #000;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    font-size: 12px;
    color: #333;
    overflow: hidden;
  }
}
.chart-series-outer{
  grid-area: series;
}
.chart-series{
  width: 100%;
  height: 100%;
}

      .apexcharts-legend {
        display: flex;
        overflow: auto;
        padding: 0 10px;
      }

      .apexcharts-legend.position-bottom, .apexcharts-legend.position-top {
        flex-wrap: wrap
      }
      .apexcharts-legend.position-right, .apexcharts-legend.position-left {
        flex-direction: column;
        bottom: 0;
      }

      .apexcharts-legend.position-bottom.left, .apexcharts-legend.position-top.left, .apexcharts-legend.position-right, .apexcharts-legend.position-left {
        justify-content: flex-start;
      }

      .apexcharts-legend.position-bottom.center, .apexcharts-legend.position-top.center {
        justify-content: center;  
      }

      .apexcharts-legend.position-bottom.right, .apexcharts-legend.position-top.right {
        justify-content: flex-end;
      }

      .apexcharts-legend-series {
        cursor: pointer;
        line-height: normal;
      }

      .apexcharts-legend.position-bottom .apexcharts-legend-series, .apexcharts-legend.position-top .apexcharts-legend-series{
        display: flex;
        align-items: center;
      }

      .apexcharts-legend-text {
        position: relative;
        font-size: 14px;
      }

      .apexcharts-legend-text *, .apexcharts-legend-marker * {
        pointer-events: none;
      }

      .apexcharts-legend-marker {
        position: relative;
        display: inline-block;
        cursor: pointer;
        margin-right: 3px;
      }
      
      .apexcharts-legend.right .apexcharts-legend-series, .apexcharts-legend.left .apexcharts-legend-series{
        display: inline-block;
      }

      .apexcharts-legend-series.no-click {
        cursor: auto;
      }

      .apexcharts-legend .apexcharts-hidden-zero-series, .apexcharts-legend .apexcharts-hidden-null-series {
        display: none !important;
      }

      .inactive-legend {
        opacity: 0.45;
      }
</style>
