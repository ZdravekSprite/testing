<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>test</title>
  <script src="https://unpkg.com/lightweight-charts/dist/lightweight-charts.standalone.production.js"></script>
</head>

<body>
  @foreach($symbols as $symbol)
  <div style="float: left; padding: 1px;" id="chart_{{$symbol[0]}}"></div>
  @endforeach
  <script>
    @foreach($symbols as $symbol)
    var chart_{{ $symbol[0] }} = LightweightCharts.createChart(document.getElementById('chart_{{ $symbol[0] }}'), {
      width: 470,
      height: 230,
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
      timeScale: {
        borderColor: 'rgba(197, 203, 206, 0.8)',
        timeVisible: true,
        secondsVisible: false,
      },
    });

    chart_{{ $symbol[0] }}.applyOptions({
      watermark: {
        color: 'rgba(170, 175, 180, 0.5)',
        visible: true,
        text: '{{ $symbol[0] }}',
        fontSize: 22,
        horzAlign: 'left',
        vertAlign: 'top',
      },
    });

    chart_{{ $symbol[0] }}.subscribeClick(function(param){
      console.log(`An user clicks at (${param.point.x}, ${param.point.y}) point, the time is ${param.time}`);
      console.log(candleSeries_{{ $symbol[0] }}.coordinateToPrice(param.point.x));
    });

    var candleSeries_{{ $symbol[0] }} = chart_{{ $symbol[0] }}.addCandlestickSeries({
      upColor: '#00ff00',
      downColor: '#ff0000',
      borderDownColor: 'rgba(255, 144, 0, 1)',
      borderUpColor: 'rgba(255, 144, 0, 1)',
      wickDownColor: 'rgba(255, 144, 0, 1)',
      wickUpColor: 'rgba(255, 144, 0, 1)',
      priceFormat: { type: 'price', minMove: 0.0001, precision: 4 },
    });

    // create a horizontal price line at a certain price level.
    @if(isset($symbol[1]))
    const buypriceLine{{ $symbol[0] }} = candleSeries_{{ $symbol[0] }}.createPriceLine({
          price: {{ $symbol[1] }},
          color: 'red',
          lineWidth: 2,
          lineStyle: LightweightCharts.LineStyle.Dotted,
          axisLabelVisible: true,
    });
    @endif
    @if(isset($symbol[2]))
    const sellpriceLine{{ $symbol[0] }} = candleSeries_{{ $symbol[0] }}.createPriceLine({
          price: {{ $symbol[2] }},
          color: 'green',
          lineWidth: 2,
          lineStyle: LightweightCharts.LineStyle.Dotted,
          axisLabelVisible: true,
    });
    @endif

// set markers
    candleSeries_{{ $symbol[0] }}.setMarkers([
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

    const histogramSeries_{{ $symbol[0] }} = chart_{{ $symbol[0] }}.addHistogramSeries({
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

    fetch('https://api.binance.com/api/v3/klines?symbol={{ $symbol[0] }}&interval=1m&limit=1000')
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
        candleSeries_{{ $symbol[0] }}.setData(objs);
        histogramSeries_{{ $symbol[0] }}.setData(objs);
      })

    @endforeach

    // /stream?streams=<streamName1>/<streamName2>/<streamName3>
    //var binanceSocket = new WebSocket("wss://stream.binance.com:9443/ws/btcusdt@kline_1m");
    //var binanceSocket = new WebSocket("wss://stream.binance.com:9443/stream?streams=btcusdt@kline_1m/ethusdt@kline_1m/bnbusdt@kline_1m/ethbtc@kline_1m");
    var binanceSocket = new WebSocket("wss://stream.binance.com:9443/stream?streams={{ $link }}");

    binanceSocket.onmessage = function(event) {
      var message = JSON.parse(event.data);
      if (message.stream == 'bnbusdt@kline_1m') document.title = message.data.k.c;
      //console.log('message', message)
      @foreach($symbols as $symbol)
      if (message.stream == '{{ strtolower($symbol[0]) }}@kline_1m') {
        var candlestick = message.data.k;
        candleSeries_{{ $symbol[0] }}.update({
          time: candlestick.t / 1000 + 60*60*2,
          open: candlestick.o,
          high: candlestick.h,
          low: candlestick.l,
          close: candlestick.c,
        });
        histogramSeries_{{ $symbol[0] }}.update({
          time: candlestick.t / 1000 + 60*60*2,
          value: candlestick.v * candlestick.c,
          color: candlestick.o > candlestick.c ? 'rgba(255,82,82, 0.8)' : 'rgba(0, 150, 136, 0.8)'
        });
      }
      @endforeach

      //var candlestick = message.k;

      //console.log('candlestick', candlestick)

    }

  </script>
</body>

</html>
