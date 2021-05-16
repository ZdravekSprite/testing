<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $coin }}</title>
  <script src="https://unpkg.com/lightweight-charts/dist/lightweight-charts.standalone.production.js"></script>
  <style>
  .floating-tooltip-2 {
    width: 75px;
    height: 100px;
    position: absolute;
    display: none;
    padding: 8px;
    box-sizing: border-box;
    font-size: 12px;
    color: #131722;
    background-color: rgba(255, 255, 255, 1);
    text-align: left;
    z-index: 1000;
    top: 12px;
    left: 12px;
    pointer-events: none;
    border: 1px solid rgba(255, 70, 70, 1);
    border-radius: 2px;
  }
  </style>
</head>

<body>
  <script>
    var chartWidth = 930;
    var chartHeight = 310;

    var toolTipWidth = 100;
    var toolTipHeight = 80;
    var toolTipMargin = 15;

    @foreach(['BUSD','BTC','ETH'] as $quote)
    @foreach(['1h','1m'] as $tick)
    var container{{ $tick }}_{{ $coin . $quote }} = document.createElement('div');
    container{{ $tick }}_{{ $coin . $quote }}.id = "chart{{ $tick }}_{{ $coin . $quote }}";
    container{{ $tick }}_{{ $coin . $quote }}.style.cssText = 'float: left; padding: 1px;';

    document.body.appendChild(container{{ $tick }}_{{ $coin . $quote }});

    var chart{{ $tick }}_{{ $coin . $quote }} = LightweightCharts.createChart(container{{ $tick }}_{{ $coin . $quote }}, {
      width: chartWidth,
      height: chartHeight,
      layout: {
        backgroundColor: '#000000',
        textColor: 'rgba(255, 255, 255, 0.9)',
      },
      grid: {
        vertLines: {
          color: 'rgba(197, 203, 206, 0.5)',
        },
        horzLines: {
          color: 'rgba(197, 203, 206, 0.5)',
        },
      },
      crosshair: {
        mode: LightweightCharts.CrosshairMode.Normal,
      },
      priceScale: {
        borderColor: 'rgba(197, 203, 206, 0.8)',
      },
      rightPriceScale: {
        scaleMargins: {
          top: 0.2,
          bottom: 0.1,
        },
      },
      timeScale: {
        rightOffset: 2,
        borderColor: 'rgba(197, 203, 206, 0.8)',
        timeVisible: true,
        secondsVisible: false,
      },
    });

    chart{{ $tick }}_{{ $coin . $quote }}.applyOptions({
      watermark: {
        color: 'rgba(170, 175, 180, 0.5)',
        visible: true,
        text: '{{ $coin . $quote }}',
        fontSize: 22,
        horzAlign: 'left',
        vertAlign: 'top',
      },
    });

    chart{{ $tick }}_{{ $coin . $quote }}.subscribeClick(function(param){
      console.log(`An user clicks at (${param.point.x}, ${param.point.y}) point, the time is ${param.time}`);
      console.log(candleSeries{{ $tick }}_{{ $coin . $quote }}.coordinateToPrice(param.point.x));
    });

    var toolTip{{ $tick }}_{{ $coin . $quote }} = document.createElement('div');
    toolTip{{ $tick }}_{{ $coin . $quote }}.className = 'floating-tooltip-2';
    container{{ $tick }}_{{ $coin . $quote }}.appendChild(toolTip{{ $tick }}_{{ $coin . $quote }});

// update tooltip
    chart{{ $tick }}_{{ $coin . $quote }}.subscribeCrosshairMove(function(param) {
      if (!param.time || param.point.x < 0 || param.point.x > chartWidth || param.point.y < 0 || param.point.y > chartHeight) {
        toolTip{{ $tick }}_{{ $coin . $quote }}.style.display = 'none';
        return;
      }

      toolTip{{ $tick }}_{{ $coin . $quote }}.style.display = 'block';
      var price{{ $tick }}_{{ $coin . $quote }} = param.seriesPrices.get(candleSeries{{ $tick }}_{{ $coin . $quote }});
      var txt{{ $tick }}_{{ $coin . $quote }} = ((price{{ $tick }}_{{ $coin . $quote }}.close - price{{ $tick }}_{{ $coin . $quote }}.open ) / price{{ $tick }}_{{ $coin . $quote }}.open );
      toolTip{{ $tick }}_{{ $coin . $quote }}.innerHTML = '<div style="font-size: 10px; color: rgba(255, 70, 70, 1)">{{ $coin . $quote }}</div>' +
        '<div style="font-size: 12px; margin: 2px 0px">' + (txt{{ $tick }}_{{ $coin . $quote }}*100).toFixed(2) + '%</div>' +
        '<div style="font-size: 10px; margin: 2px 0px">' + price{{ $tick }}_{{ $coin . $quote }}.high*1 + '</div>' +
        '<div style="font-size: 10px; margin: 2px 0px">' + price{{ $tick }}_{{ $coin . $quote }}.open*1 + '</div>' +
        '<div style="font-size: 10px; margin: 2px 0px">' + price{{ $tick }}_{{ $coin . $quote }}.close*1 + '</div>' +
        '<div style="font-size: 10px; margin: 2px 0px">' + price{{ $tick }}_{{ $coin . $quote }}.low*1 + '</div>';

      var y{{ $tick }}_{{ $coin . $quote }} = container{{ $tick }}_{{ $coin . $quote }}.offsetTop + param.point.y;
      var x{{ $tick }}_{{ $coin . $quote }} = container{{ $tick }}_{{ $coin . $quote }}.offsetLeft + param.point.x;

      var left{{ $tick }}_{{ $coin . $quote }} = x{{ $tick }}_{{ $coin . $quote }} + toolTipMargin;
      if (left{{ $tick }}_{{ $coin . $quote }} > chartWidth - toolTipWidth) {
        left{{ $tick }}_{{ $coin . $quote }} = x{{ $tick }}_{{ $coin . $quote }} - toolTipMargin - toolTipWidth;
      }

      var top{{ $tick }}_{{ $coin . $quote }} = y{{ $tick }}_{{ $coin . $quote }} + toolTipMargin;
      if (top{{ $tick }}_{{ $coin . $quote }} > chartHeight - toolTipHeight) {
        top{{ $tick }}_{{ $coin . $quote }} = y{{ $tick }}_{{ $coin . $quote }} - toolTipHeight - toolTipMargin;
      }

      toolTip{{ $tick }}_{{ $coin . $quote }}.style.left = left{{ $tick }}_{{ $coin . $quote }} + 'px';
      toolTip{{ $tick }}_{{ $coin . $quote }}.style.top = top{{ $tick }}_{{ $coin . $quote }} + 'px';
    });

    var candleSeries{{ $tick }}_{{ $coin . $quote }} = chart{{ $tick }}_{{ $coin . $quote }}.addCandlestickSeries({
      upColor: '#00ff00',
      downColor: '#ff0000',
      borderDownColor: 'rgba(255, 144, 0, 1)',
      borderUpColor: 'rgba(255, 144, 0, 1)',
      wickDownColor: 'rgba(255, 144, 0, 1)',
      wickUpColor: 'rgba(255, 144, 0, 1)',
      priceFormat: { type: 'price', minMove: 0.0001, precision: 4 },
      scaleMargins: {
        top: 1,
        bottom: 0.05,
      },
    });

    fetch('https://api.binance.com/api/v3/klines?symbol={{ $coin . $quote }}&interval={{ $tick }}&limit=1000')
      .then((r) => r.json())
      .then((response) => {
        //console.log('response_binance', response)
        var objs = response.map(function(x) {
          return {
            time: x[0] / 1000 + 60*60*2,
            open: x[1],
            high: x[2],
            low: x[3],
            close: x[4],
            value: x[5]*x[4],
            color: x[1] > x[4] ? 'rgba(255,82,82, 0.8)' : 'rgba(0, 150, 136, 0.8)'
          };
        });
        console.log(objs);
        //console.log('response_data', data)
        candleSeries{{ $tick }}_{{ $coin . $quote }}.setData(objs);
        histogramSeries{{ $tick }}_{{ $coin . $quote }}.setData(objs);
      })

    const histogramSeries{{ $tick }}_{{ $coin . $quote }} = chart{{ $tick }}_{{ $coin . $quote }}.addHistogramSeries({
      color: '#26a69a',
      priceFormat: {
        type: 'volume',
      },
      priceScaleId: '',
      scaleMargins: {
        top: 0.90,
        bottom: 0,
      },
    });

    @endforeach
    @endforeach

    // /stream?streams=<streamName1>/<streamName2>/<streamName3>
    //var binanceSocket = new WebSocket("wss://stream.binance.com:9443/ws/btcusdt@kline_1m");
    var binanceSocket = new WebSocket("wss://stream.binance.com:9443/stream?streams={{ strtolower($coin) }}busd@kline_1m/{{ strtolower($coin) }}btc@kline_1m/{{ strtolower($coin) }}eth@kline_1m");

    binanceSocket.onmessage = function(event) {
      var message = JSON.parse(event.data);

    //console.log('message', message)
    @foreach(['BUSD','BTC','ETH'] as $quote)
      if (message.stream == '{{ strtolower($coin . $quote) }}@kline_1m') {
        var candlestick = message.data.k;
        candleSeries1m_{{ $coin . $quote }}.update({
          time: candlestick.t / 1000 + 60*60*2,
          open: candlestick.o,
          high: candlestick.h,
          low: candlestick.l,
          close: candlestick.c,
        });
        histogramSeries1m_{{ $coin . $quote }}.update({
          time: candlestick.t / 1000 + 60*60*2,
          value: candlestick.v * candlestick.c,
          color: candlestick.o > candlestick.c ? 'rgba(255,82,82, 0.8)' : 'rgba(0, 150, 136, 0.8)'
        });
      }
    @endforeach
    }

  </script>
</body>

</html>
