<template>
  <div :style="{width:width+'px',height:height+'px',color:'#000',backgroundColor:'#fff'}">
    <div class="chart-outer">
      <div class="chart-header-outer">
        <div v-if="chartOptions.title.text" class="chart-header-title">
          {{chartOptions.title.text}}
        </div>
        <div class="chart-header-legends">
          <span v-for="(series, seriesIndex) in chartOptions.series" class="chart-header-legend">
            <span
              class="chart-header-legend-marker"
              :style="{
                backgroundColor: chartOptions.stroke.colors[seriesIndex]
              }"
            ></span>
            <span class="chart-header-legend-text">{{series.name}}</span>
          </span>
        </div>
      </div>
      <div class="chart-yaxis-outer">
        <div class="chart-yaxis">
          <span v-for="category in chartOptions.xaxis.categories">{{category}}</span>
        </div>
      </div>
      <div class="chart-grid-outer">
        <div class="chart-grid" v-for="i in 6"></div>
      </div>
      <div class="chart-series-outer">
        <div class="chart-series" ref="series">
          <div class="chart-series-category" :style="{margin:xaxisGap+' 0'}" v-for="(category,categoryIndex) in chartOptions.xaxis.categories">
            <div class="chart-series-stack" v-for="stack in chartOptions.stack">
              <div
                class="chart-series-bar-outer"
                v-for="seriesId in stack.series"
                :data-series-id="seriesId"
                :data-category-index="categoryIndex"
                :style="{
                  width:'0%',
                  height:'100%'
                }"
              >
                <div
                  class="chart-series-bar"
                  :style="{
                    borderWidth: strokeWidth,
                    borderStyle:'solid',
                    borderColor: chartOptions.stroke.colors[seriesId],
                    backgroundColor: chartOptions.fill.colors[seriesId]
                  }"
                >{{chartOptions.series[seriesId].data[categoryIndex]}}{{chartOptions.dataLabels.suffix}}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    chartOptions: {type: Object},
    width: {},
    height: {},
  },
  data(){
    return{
      seriesAreaWidth: 0,
      seriesAreaHeight: 0,
    }
  },
  mounted() {
    this.seriesAreaWidth = this.$refs.series.offsetWidth;
    this.seriesAreaHeight = this.$refs.series.offsetHeight;
    this.setBarAnimation()
  },
  computed: {
    seriesMaxValue: function(){
      const sumArray = []
      this.chartOptions.stack.forEach((stack,stackIndex)=>{
        // stack.series = [1,2]
        const l = this.chartOptions.xaxis.categories.length
        for(let i=0;i<l;i++){
          sumArray[stackIndex*l+i] = 0
          stack.series.forEach(seriesId=>{
            sumArray[stackIndex*l+i] += this.chartOptions.series[seriesId].data[i]
          })
        }
      })
      return Math.max(...sumArray)
    },
    strokeWidth: function(){
      return '2px'
    },
    xaxisGap: function(){
      return this.chartOptions.xaxis.gap || '10px'
    },
  },
  watch: {
    'chartOptions': {
      handler:  function(){
        console.log('changed')
        setTimeout(this.setBarAnimation, 0)
      },
      deep: true
    }
  },
  methods: {
    setBarAnimation(){
      if(this.chartOptions.animations === false){
        // アニメーションなし
        [...this.$refs.series.querySelectorAll('.chart-series-bar-outer')].map((el,i)=>{
          el.style.width = ( this.chartOptions.series[el.getAttribute('data-series-id')].data[el.getAttribute('data-category-index')] / this.seriesMaxValue * 100 )+'%'
        })
      }else{
        // アニメーションあり
        this.$anime({
          targets: this.$refs.series.querySelectorAll('.chart-series-bar-outer'),
          width: (el,i)=>{
            return ( this.chartOptions.series[el.getAttribute('data-series-id')].data[el.getAttribute('data-category-index')] / this.seriesMaxValue * 100 )+'%'
          },
          duration: 1000,
          delay: 0,
          easing: 'easeOutQuint'
        })
      }
    },
  }
}
</script>

<style scoped lang="scss">
.chart-outer{
  width: 100%;
  height: 100%;
  display: grid;
  grid-template-columns: max-content 1fr;
  grid-template-rows: max-content 1fr;
  grid-template-areas: "header header" "yaxis series";
  grid-gap: 10px;
  padding: 10px;
  user-select: none;
}
.chart-header-outer{
  grid-area: header;
}
.chart-header-title{
  text-align: center;
  font-size: 20px;
}
.chart-header-legends{
  text-align: center;
  font-size: 12px;
}
.chart-header-legend{
  &+&{
    margin-left: 1em;
  }
}
.chart-header-legend-marker{
  display: inline-block;
  width: 12px;
  height: 12px;
  border-radius: 2px;
  vertical-align: middle;
  margin-right: .3em;
}
.chart-yaxis-outer{
  grid-area: yaxis;
  overflow: hidden;
}
.chart-yaxis{
  display: grid;
  grid-template-columns: 1fr;
  grid-auto-rows: 1fr;
  height: 100%;
  span{
    display: flex;
    align-items: center;
    justify-content: flex-end;
    font-size: 12px;
    color: #333;
    overflow: hidden;
  }
}
.chart-grid-outer{
  grid-area: series;
  display: flex;
}
.chart-grid{
  flex: 1 1 100%;
  border-left: 1px solid #CCC;
}
.chart-series-outer{
  grid-area: series;
  overflow: hidden;
}
.chart-series{
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
}
.chart-series-category{
  flex: 0 1 100%;
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
}
.chart-series-stack{
  flex: 0 1 100%;
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: row;
  // box-shadow: inset 2px 0 0 0 #999;
  &+&{
    margin-top: 2px;
  }
}
.chart-series-bar-outer{
  flex: 0 0 auto;
  height: 100%;
  box-sizing: border-box;
  overflow: hidden;
}
.chart-series-bar{
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: flex-end;
  padding: 0 .5em;
  font-size: 12px;
}
</style>
