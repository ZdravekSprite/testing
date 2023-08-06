<html>
<head>
  <title>{{$_month->format('m.Y')}}</title>
  <meta http-equiv="content-type" content="text/html;charset=iso-8859-2">
  <meta http-equiv="Content-Style-Type" content="text/css">
  <meta http-equiv="X-UA-Compatible" content="IE=8">
  <style type="text/css" media="print">
    div.mjesec {
      page-break-inside: avoid;
    }

    .printer {
      display: none;
    }

  </style>
</head>
<body>
  <div class="content" style="width:800px;">
    <div class="mjesec">
      <img class="printer" src="/img/printer.gif" style="cursor:pointer;" onclick="window.print();return false;">
      @foreach($days as $day)
        <br /><br />{{$day->dan()}} {{$day->date->format('d.m.Y')}}
        @if($day->night != null)
          @if($day->night->format('H:i') != '00:00')
            od ponoći radio/la sam do {{ $day->night->format('H:i') }} ostatak dana
          @endif
        @endif
        @switch($day->state)
        @case(0)
          nisam radio
        @break

        @case(1)
          radio/la sam od {{ $day->start->format('H:i') }}
          do {{ $day->end->format('H:i') }}
        @break

        @case(2)
          bio sam na godišnjem
        @break

        @case(3)
          bio sam na plaćenom dopustu
        @break

        @case(4)
          bio sam na bolovanju
        @break

        @default
          ne znam
        @endswitch

      @endforeach
    </div>
  </div>
</body>
</html>
