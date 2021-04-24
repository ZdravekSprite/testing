<template>
  <div>
    <h4>I'm an klines component.</h4>
    <div id="chart">
      <apexchart
        type="candlestick"
        height="350"
        :options="chartOptions"
        :series="series"
      ></apexchart>
    </div>
  </div>
</template>

<script>
import Vue from 'vue'
import ApexCharts from 'apexcharts'
Vue.component('apexchart', ApexCharts)

export default {
  props: ["klines"],
  data: () => ({
    series: [
      {
        data: [],
      },
    ],
    chartOptions: {
      chart: {
        type: "candlestick",
        height: 350,
      },
      title: {
        text: "CandleStick Chart",
        align: "left",
      },
      xaxis: {
        type: "datetime",
      },
      yaxis: {
        tooltip: {
          enabled: true,
        },
      },
    },
  }),
  mounted() {
    console.log("Component mounted.");
  },
};
</script>
