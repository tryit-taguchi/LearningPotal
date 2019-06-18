<template>
  <div :style="{width:width+'px',height:height+'px',color:'#000',backgroundColor:'#fff',position:'relative'}">
    <svg :viewbox="chartViewbox" :width="chartWidth" :height="chartHeight">
      <text ref="title" :x="titleX" :y="titleY" :font-size="titleFontSize" :stroke="titleStroke" :fill="titleFill" :text-anchor="titleTextAnchor">{{chartOptions.title.text}}</text>
      <foreignObject :x="legendOuterX" :y="legendOuterY" :width="legendOuterWidth" :height="legendOuterHeight">
        <div class="legend-outer" ref="legendOuter">
          <div class="legend" v-for="(series, seriesIndex) in chartOptions.series">
            <span class="legend-marker"
              :style="{
                borderRadius: legendMarkerR+'px',
                width: legendMarkerSize+'px',
                height: legendMarkerSize+'px',
                backgroundColor: chartOptions.stroke.colors[seriesIndex],
              }"
            ></span>
            <span class="legend-text"
              :style="{
                fontSize: legendTextFontSize+'px',
              }"
            >{{series.name}}</span>
          </div>
        </div>
      </foreignObject>
    </svg>
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
      refs: {
        title: null,
        legendOuter: null,
      }
    }
  },
  mounted() {
    this.seriesAreaWidth = this.$refs.series.offsetWidth;
    this.seriesAreaHeight = this.$refs.series.offsetHeight;
    this.setBarAnimation()
    this.refs.title = this.$refs.title;
    this.refs.legendOuter = this.$refs.legendOuter;
    console.log(this.$refs)
    console.log(this.refs.legendOuter)
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

    chartViewbox: function(){
      return '0 0 '+this.width+' '+this.height
    },
    chartWidth: function(){
      return this.width
    },
    chartHeight: function(){
      return this.height
    },
    chartPadding: function(){
      return 10
    },

    titleX: function(){
      return this.width/2
    },
    titleY: function(){
      return this.titleHeight+10
    },
    titleFontSize: function(){
      return 20
    },
    titleStroke: function(){
      return 'none'
    },
    titleFill: function(){
      return 'black'
    },
    titleTextAnchor: function(){
      return 'middle'
    },
    titleWidth: function(){
      if(this.refs.title){
        return this.refs.title.getBBox().width
      }else{
        return 0
      }
    },
    titleHeight: function(){
      if(this.refs.title){
        return this.refs.title.getBBox().height
      }else{
        return 0
      }
    },

    legendOuterWidth: function(){
      if(this.refs.legendOuter){
        return this.refs.legendOuter.offsetWidth
      }else{
        return this.width
      }
    },
    legendOuterHeight: function(){
      if(this.refs.legendOuter){
        return this.refs.legendOuter.offsetHeight
      }else{
        return this.height
      }
    },
    legendOuterX: function(){
      return this.chartWidth/2-this.legendOuterWidth/2
    },
    legendOuterY: function(){
      return this.titleY
    },
    legendLength: function(){
      return this.chartOptions.series.length
    },
    legendTextFontSize: function(){
      return 12
    },
    legendTextWidth: function(){
      return this.$refs.legend.map(el=>el.getBBox().width)
    },
    legendTextHeight: function(){
      return this.legendTextFontSize
    },
    legendMarkerSize: function(){
      return this.legendTextFontSize
    },
    legendMarkerR: function(){
      return 2
    },
    legendWidth: function(){
      return [...new Array(this.legendLength)].map((v,i)=>this.legendMarkerSize+this.legendTextWidth[i])
    },
    legendHeight: function(){
      return [...new Array(this.legendLength)].map((v,i)=>this.legendTextHeight)
    },
  },
  watch: {
    'chartOptions': {
      handler:  function(){
        // console.log('changed')
        setTimeout(this.setBarAnimation, 0)
      },
      deep: true
    }
  },
  methods: {
    setBarAnimation(){
      this.$anime({
        targets: this.$refs.series.querySelectorAll('.chart-series-bar-outer'),
        width: (el,i)=>{
          return ( this.chartOptions.series[el.getAttribute('data-series-id')].data[el.getAttribute('data-category-index')] / this.seriesMaxValue * 100 )+'%'
        },
        duration: 1000,
        delay: 0,
        easing: 'easeOutQuint'
      })
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

  position: absolute;
  top: 0;
  left: 0;
  opacity:  .5;
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
  box-shadow: inset 2px 0 0 0 #999;
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

svg{
  position: absolute;
  top: 0;
  left: 0;
  z-index: 10;
}
.legend-outer{
  display: inline-flex;
}
.legend{
  &+&{
    margin-left: .5em;
  }
}
.legend-marker{
  display: inline-block;
  margin-right: .3em;
  vertical-align: middle;
}
.legend-text{

}
</style>
