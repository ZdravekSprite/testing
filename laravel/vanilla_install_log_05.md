## Platna lista

```
php artisan serve
npm run hot
php artisan make:migration add_go_to_days_table --table=days
```
### database\migrations\2021_03_03_090909_add_go_to_days_table.php
```
  public function up()
  {
    Schema::table('days', function (Blueprint $table) {
      $table->boolean('go')
        ->after('sick')
        ->default(false);
    });
  }
  public function down()
  {
    Schema::table('days', function (Blueprint $table) {
      $table->dropColumn('go');
    });
  }
```
```
php artisan migrate
```
### resources\views\days\index.blade.php
```
                  <div class="w-full rounded-md relative {{$day->sick ? 'bg-red' : ($day->go ? 'bg-green' : 'bg-indigo')}}-100" style="min-height: 18px;" title={{$day->date->format('d.m.Y')}}>
```
### resources\views\days\show.blade.php
```
          <p>{{ $day[0]->go ? 'bio sam' : 'nisam bio' }} na godišnjem</p>
```
### resources\views\days\create.blade.php
```
            <!-- GO -->
            <div class="mt-4">
              <x-label for="go" :value="__('Godišnji')" />
              <input id="go" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="checkbox" name="go" />
              <p>Da li ste taj dan bili na godišnjem? Ako je godišnji onda bi ostale vrijednosti trebale biti 00:00</p>
              <p>Kako nisam još bio na GO ne znam kak se računa ali pretpostavljam da kao i za bolovanje, ali ako netko zna točno slobodno javi na <a href="mailto:zdravek.sprite@gmail.com">mail zdravek.sprite@gmail.com</a></p>
            </div>
```
### resources\views\days\edit.blade.php
```
            <!-- GO -->
            <div class="mt-4">
              <x-label for="go" :value="__('Godišnji')" />
              <input id="go" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="checkbox" name="go" {{$day->go ? 'checked' : ''}} />
            </div>
```
### app\Http\Controllers\DayController.php
```
  public function create(Request $request)
  {
    $day = new Day;
    if (null != $request->input('date')) {
      $day->date = $request->input('date');
      if ($request->input('sick') == true) $day->sick = true;
      if ($request->input('go') == true) $day->go = true;
      if ($request->input('start') != null) $day->start = $request->input('start');
    }
    return view('days.create')->with(compact('day'));
  }

  public function store(Request $request)
  {
    $this->validate($request, [
      'date' => 'required'
    ]);
    $old_day = Day::where('user_id', '=', Auth::user()->id)->where('date', '=', date('Y-m-d', strtotime($request->input('date'))))->get();
    $day = new Day;
    $day->date = $request->input('date');
    $day->user_id = Auth::user()->id;
    if (null != $request->input('sick')) $day->sick = $request->input('sick') == 'on' ? true : false;
    if (null != $request->input('go')) $day->go = $request->input('go') == 'on' ? true : false;
    if (null != $request->input('night_duration')) $day->night_duration = $request->input('night_duration') ? $request->input('night_duration') : $day->night_duration;
    $day->start = $request->input('start');
    $day->duration = $request->input('duration');
    if (count($old_day) > 0) return view('days.edit')->with(compact('old_day', 'day'));
    $day->save();
    return redirect(route('month').'/'.$day->date->format('m.Y'))->with('success', 'Day Updated');
  }

  public function update(Request $request, $date)
  {
    $day = Day::where('user_id', '=', Auth::user()->id)->where('date', '=', date('Y-m-d', strtotime($date)))->get();
    if (null != $request->input('sick')) $day[0]->sick = $request->input('sick') == 'on' ? true : false;
    if (null != $request->input('go')) $day[0]->go = $request->input('go') == 'on' ? true : false;
    $day[0]->night_duration = $request->input('night_duration') ? $request->input('night_duration') : $day[0]->night_duration;
    $day[0]->start = $request->input('start');
    $day[0]->duration = $request->input('duration');
    $day[0]->save();
    return redirect(route('days.show', ['day' => $day[0]->date->format('d.m.Y')]))->with('success', 'Day Updated');
  }
```
### app\Http\Controllers\PlatnaLista.php
```
    $hoursNormGO = 0;
    $daysGO = 0;

      if ($daysColection->where('date', '=', $from->addDays($i))->where('go', '=', true)->first() != null) {
        $hoursNormGO += $def_h;
        if ($def_h > 0 ) $daysGO++;
      }

    // 1.1. Za redoviti rad
    $hoursWorkNorm = $hoursNorm - $hoursNormHoli - $hoursNormSick - $hoursNormGO;
    $h1_1 = $minWork / 60 > $hoursWorkNorm ? $hoursWorkNorm : $minWork / 60;
    $data['1.1.h'] = number_format($h1_1, 2, ',', '.'); //'158,00';
    $data['1.1.kn'] = number_format($h1_1 * $perHour, 2, ',', '.'); //'4.867,98';
    // 1.4 Za prekovremeni rad
    $h1_4 = $prekovremeni;
    $overWork = $minWork / 60 - $hoursWorkNorm;

    $data['1.4.h'] = number_format($h1_4, 2, ',', '.') . ' (' . $overWork . ')'; //'24,00';
    $data['1.4.kn'] = number_format($h1_4 * $perHour * 1.5, 2, ',', '.'); //'1.109,16';
    // 1.x Za godišnji
    $h1_go = $hoursNormGO;

    $data['1.go.h'] = number_format($h1_go, 2, ',', '.') . ' (' . $daysGO . ')';
    $data['1.go.kn'] = number_format($h1_go * $perHour, 2, ',', '.');

    // 4. ZBROJENI IZNOSI PRIMITAKA PO SVIM OSNOVAMA PO STAVKAMA 1. DO 3.
    $kn5 = ($h1_1 + $h1_4 * 1.5 + $hoursNormHoli + $hoursNormSick * 0.7588 + $hoursNormGO + $minWorkSunday / 60 * 0.35 + $minWorkHoli / 60 * 0.5) * $perHour;
```
### resources\views\platna-lista.blade.php
```
              @if($data['1.go.h'] > 0)
              <tr>
                <td class="w-3/4 border p-2 pl-6" colspan="2">GO (pretpostavljam da se ovak računa)</td>
                <td class="w-1/8 border p-2 text-center">{{ $data['1.go.h'] }}</td>
                <td class="w-1/8 border p-2 text-right">{{ $data['1.go.kn'] }}</td>
              </tr>
              @endif
```
```
git add .
git commit -am "add GO [laravel]"
```