<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>test</title>
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
    var bnb = '';
    var btc = '';
    var eth = '';

    var chartWidth = 470;
    var chartHeight = 310;

    var toolTipWidth = 100;
    var toolTipHeight = 80;
    var toolTipMargin = 15;

    @foreach($symbols as $symbol)
    @foreach(['1h','1m'] as $tick)
    var container{{ $tick }}_{{$symbol[0]}} = document.createElement('div');
    container{{ $tick }}_{{$symbol[0]}}.id = "chart{{$tick}}_{{$symbol[0]}}";
    container{{ $tick }}_{{$symbol[0]}}.style.cssText = 'float: left; padding: 1px;';

    document.body.appendChild(container{{ $tick }}_{{$symbol[0]}});

    var chart{{ $tick }}_{{ $symbol[0] }} = LightweightCharts.createChart(container{{ $tick }}_{{$symbol[0]}}, {
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

    chart{{ $tick }}_{{ $symbol[0] }}.applyOptions({
      watermark: {
        color: 'rgba(170, 175, 180, 0.5)',
        visible: true,
        text: '{{ $symbol[0] }}',
        fontSize: 22,
        horzAlign: 'left',
        vertAlign: 'top',
      },
    });

    chart{{ $tick }}_{{ $symbol[0] }}.subscribeClick(function(param){
      console.log(`An user clicks at (${param.point.x}, ${param.point.y}) point, the time is ${param.time}`);
      console.log(candleSeries{{ $tick }}_{{ $symbol[0] }}.coordinateToPrice(param.point.x));
    });

    var toolTip{{ $tick }}_{{ $symbol[0] }} = document.createElement('div');
    toolTip{{ $tick }}_{{ $symbol[0] }}.className = 'floating-tooltip-2';
    container{{ $tick }}_{{ $symbol[0] }}.appendChild(toolTip{{ $tick }}_{{ $symbol[0] }});

// update tooltip
    chart{{ $tick }}_{{ $symbol[0] }}.subscribeCrosshairMove(function(param) {
      if (!param.time || param.point.x < 0 || param.point.x > chartWidth || param.point.y < 0 || param.point.y > chartHeight) {
        toolTip{{ $tick }}_{{ $symbol[0] }}.style.display = 'none';
        return;
      }

      toolTip{{ $tick }}_{{ $symbol[0] }}.style.display = 'block';
      var price{{ $tick }}_{{ $symbol[0] }} = param.seriesPrices.get(candleSeries{{ $tick }}_{{ $symbol[0] }});
      //console.log(param.time);
      var txt{{ $tick }}_{{ $symbol[0] }} = ((price{{ $tick }}_{{ $symbol[0] }}.close - price{{ $tick }}_{{ $symbol[0] }}.open ) / price{{ $tick }}_{{ $symbol[0] }}.open );
      toolTip{{ $tick }}_{{ $symbol[0] }}.innerHTML = '<div style="font-size: 10px; color: rgba(255, 70, 70, 1)">{{ $symbol[0] }}</div>' +
        //'<div style="font-size: 10px; margin: 2px 0px">' + new Date(param.time * 1000) + '%</div>' +
        '<div style="font-size: 12px; margin: 2px 0px">' + (txt{{ $tick }}_{{ $symbol[0] }}*100).toFixed(2) + '%</div>' +
        '<div style="font-size: 10px; margin: 2px 0px">' + price{{ $tick }}_{{ $symbol[0] }}.high*1 + '</div>' +
        '<div style="font-size: 10px; margin: 2px 0px">' + price{{ $tick }}_{{ $symbol[0] }}.open*1 + '</div>' +
        '<div style="font-size: 10px; margin: 2px 0px">' + price{{ $tick }}_{{ $symbol[0] }}.close*1 + '</div>' +
        '<div style="font-size: 10px; margin: 2px 0px">' + price{{ $tick }}_{{ $symbol[0] }}.low*1 + '</div>';

      var y{{ $tick }}_{{ $symbol[0] }} = container{{ $tick }}_{{$symbol[0]}}.offsetTop + param.point.y;
      var x{{ $tick }}_{{ $symbol[0] }} = container{{ $tick }}_{{$symbol[0]}}.offsetLeft + param.point.x;

      var left{{ $tick }}_{{ $symbol[0] }} = x{{ $tick }}_{{ $symbol[0] }} + toolTipMargin;
      if (left{{ $tick }}_{{ $symbol[0] }} > chartWidth - toolTipWidth) {
        left{{ $tick }}_{{ $symbol[0] }} = x{{ $tick }}_{{ $symbol[0] }} - toolTipMargin - toolTipWidth;
      }

      var top{{ $tick }}_{{ $symbol[0] }} = y{{ $tick }}_{{ $symbol[0] }} + toolTipMargin;
      if (top{{ $tick }}_{{ $symbol[0] }} > chartHeight - toolTipHeight) {
        top{{ $tick }}_{{ $symbol[0] }} = y{{ $tick }}_{{ $symbol[0] }} - toolTipHeight - toolTipMargin;
      }

      toolTip{{ $tick }}_{{ $symbol[0] }}.style.left = left{{ $tick }}_{{ $symbol[0] }} + 'px';
      toolTip{{ $tick }}_{{ $symbol[0] }}.style.top = top{{ $tick }}_{{ $symbol[0] }} + 'px';
    });

    const histogramSeries{{ $tick }}_{{ $symbol[0] }} = chart{{ $tick }}_{{ $symbol[0] }}.addHistogramSeries({
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

    const lineSeries{{ $tick }}_{{ $symbol[0] }} = chart{{ $tick }}_{{ $symbol[0] }}.addLineSeries({
      priceScaleId: 'left',
      color: '#f48fb1',
      lineStyle: 0,
      lineWidth: 1,
      drawCrosshairMarker: true,
      crosshairMarkerRadius: 1,
      lineType: 1,
    });

    const candleSeries{{ $tick }}_{{ $symbol[0] }} = chart{{ $tick }}_{{ $symbol[0] }}.addCandlestickSeries({
      upColor: '#00ff00',
      downColor: '#ff0000',
      borderDownColor: 'rgba(255, 144, 0, 1)',
      borderUpColor: 'rgba(255, 144, 0, 1)',
      wickDownColor: 'rgba(255, 144, 0, 1)',
      wickUpColor: 'rgba(255, 144, 0, 1)',
      priceFormat: { type: 'price', minMove: 0.0001, precision: 4 },
      scaleMargins: {
        top: 1,
        bottom: 0.1,
      },
    });

    // create a horizontal price line at a certain price level.
    @if(isset($symbol[1]))
    @foreach($symbol[1] as $key => $buy)
    const buypriceLine{{ $tick }}{{ $symbol[0] }}{{ $key }} = candleSeries{{ $tick }}_{{ $symbol[0] }}.createPriceLine({
          price: {{ $buy }},
          color: 'red',
          lineWidth: 2,
          lineStyle: LightweightCharts.LineStyle.Dotted,
          axisLabelVisible: true,
    });
    @endforeach
    @endif
    @if(isset($symbol[2]))
    @foreach($symbol[2] as $key => $sell)
    const sellpriceLine{{ $tick }}{{ $symbol[0] }}{{ $key }} = candleSeries{{ $tick }}_{{ $symbol[0] }}.createPriceLine({
          price: {{ $sell }},
          color: 'green',
          lineWidth: 2,
          lineStyle: LightweightCharts.LineStyle.Dotted,
          axisLabelVisible: true,
    });
    @endforeach
    @endif

    fetch('https://api.binance.com/api/v3/klines?symbol={{ $symbol[0] }}&interval={{ $tick }}&limit=1000')
      .then((r) => r.json())
      .then((r) => {
        //console.log('response_binance', response)
        var objs = r.map(function(x) {
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
        candleSeries{{ $tick }}_{{ $symbol[0] }}.setData(objs);
        histogramSeries{{ $tick }}_{{ $symbol[0] }}.setData(objs);
        lineSeries{{ $tick }}_{{ $symbol[0] }}.setData(objs.map(function(x) {
          return {
            time: x.time,
            value: x.value*(x.close - x.open)*(x.high - x.low)
          };
        }));
      })

// set markers
    candleSeries{{ $tick }}_{{ $symbol[0] }}.setMarkers([
      @foreach($symbol[3] as $marker)
      {
        time: {{ $marker->time + 60*60*2 }},
        position: '{{ $marker->position }}',
        color: '{{ $marker->color }}',
        shape: '{{ $marker->shape }}',
        id: '{{ $marker->id }}',
        text: '{{ $marker->text }}',
        size: 1,
      },
      @endforeach
    ]);

    @endforeach

    @endforeach

    // /stream?streams=<streamName1>/<streamName2>/<streamName3>
    //var binanceSocket = new WebSocket("wss://stream.binance.com:9443/ws/btcusdt@kline_1m");
    //var binanceSocket = new WebSocket("wss://stream.binance.com:9443/stream?streams=btcusdt@kline_1m/ethusdt@kline_1m/bnbusdt@kline_1m/ethbtc@kline_1m");
    var binanceSocket = new WebSocket("wss://stream.binance.com:9443/stream?streams={{ $link }}");

    binanceSocket.onmessage = function(event) {
      var message = JSON.parse(event.data);

      if (message.stream == 'bnbbusd@kline_1m') bnb = message.data.k.c*1;
      if (message.stream == 'ethbusd@kline_1m') eth = message.data.k.c*1;
      if (message.stream == 'btcbusd@kline_1m') btc = message.data.k.c*1;
      document.title = bnb + ' ' + eth + ' ' + btc;

      //console.log('message', message)
      @foreach($symbols as $symbol)
      if (message.stream == '{{ strtolower($symbol[0]) }}@kline_1m') {
        var candlestick = message.data.k;
        candleSeries1m_{{ $symbol[0] }}.update({
          time: candlestick.t / 1000 + 60*60*2,
          open: candlestick.o,
          high: candlestick.h,
          low: candlestick.l,
          close: candlestick.c,
        });
        histogramSeries1m_{{ $symbol[0] }}.update({
          time: candlestick.t / 1000 + 60*60*2,
          value: candlestick.v * candlestick.c,
          color: candlestick.o > candlestick.c ? 'rgba(255,82,82, 0.8)' : 'rgba(0, 150, 136, 0.8)'
        });
        lineSeries1m_{{ $symbol[0] }}.update({
          time: candlestick.t / 1000 + 60*60*2,
          value: candlestick.v * candlestick.c * (candlestick.c - candlestick.o) * (candlestick.h - candlestick.l),
        });
      }
      @endforeach
      //var candlestick = message.k;
      //console.log('candlestick', candlestick)
    }

  </script>
</body>

</html>
