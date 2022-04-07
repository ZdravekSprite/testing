<html>
<head>
  <title>{{$month->slug()}}</title>
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
      <table class="table-auto w-full text-sm md:text-base">
        <thead>
          <tr>
            <th>dan</th>
            <th>datum</th>
            <th>status</th>
            <th>sati rada</th>
          </tr>
        </thead>
        <tbody>
          @foreach($days as $day)
          <tr>
            <td>{{$day->dan()}}</td>
            <td>{{$day->date->format('d.m.Y')}}</td>
            <td>
              @if($day->night != null)
              @if($day->night->format('H:i') != '00:00')
              od ponoći radio/la sam do {{ $day->night->format('H:i') }}<br /> ostatak dana
              @endif
              @endif
              @switch($day->state)
              @case(0)
              nisam radio/la
              @break

              @case(1)
              radio/la sam od {{ $day->start->format('H:i') }}
              do {{ $day->end->format('H:i') != '00:00' ? $day->end->format('H:i') : '24:00' }}
              @break

              @case(2)
              bio/la sam na godišnjem
              @break

              @case(3)
              bio/la sam na plaćenom dopustu
              @break

              @case(4)
              bio/la sam na bolovanju
              @break

              @default
              ne znam
              @endswitch
            </td>
            <td style="text-align: center;">{{ date('H:i', mktime(0,$day->minWork())) }}</td>
          </tr>
          @endforeach
        </tbody>
        <tfoot>
          <tr>
            <th></th>
            <th></th>
            <th></th>
            <th>{{ number_format($month->data()->min/60, 2, ',', '.') }}</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</body>
</html>
