<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Day;
use App\Models\Draw;
use App\Models\Holiday;
use App\Models\Month;
use App\Models\User;

class ExportController extends Controller
{
  public function days()
  {
    $arrayData = Day::all()->map(fn ($d) => [
      'date' => $d->date->format('Y-m-d'),
      'user' => $d->user_id ? User::findOrFail($d->user_id)->name : 'unknown',
      'state' => $d->state,
      'night' => $d->night ? $d->night->format('H:i') : '0:00',
      'start' => $d->start ? $d->start->format('H:i') : '0:00',
      'end' => $d->end ? $d->end->format('H:i') : '0:00',
    ]);
    $fileName = 'days.csv';
    $columns = array_keys($arrayData[0]);
    $file = fopen(public_path('temp/' . $fileName), 'w');
    fputcsv($file, $columns);
    foreach ($arrayData as $data) {
      fputcsv($file, $data);
    }
    fclose($file);
    return redirect()->route('dashboard');
  }

  public function holidays()
  {
    $arrayData = Holiday::all()->map(fn ($h) => [
      'date' => $h->date->format('Y-m-d'),
      'name' => $h->name,
    ]);
    $fileName = 'holidays.csv';
    $columns = array_keys($arrayData[0]);
    $file = fopen(public_path('temp/' . $fileName), 'w');
    fputcsv($file, $columns);
    foreach ($arrayData as $data) {
      fputcsv($file, $data);
    }
    fclose($file);
    return redirect()->route('dashboard');
  }
  
  public function draws()
  {
    $arrayData = Draw::all()->map(fn ($d) => [
      'date' => $d->date->format('Y-m-d H:i'),
      'name' => $d->name,
      'no01' => $d->no01,
      'no02' => $d->no02,
      'no03' => $d->no03,
      'no04' => $d->no04,
      'no05' => $d->no05,
      'bo01' => $d->bo01,
      'bo02' => $d->bo02,
    ]);
    $fileName = 'draws.csv';
    $columns = array_keys($arrayData[0]);
    $file = fopen(public_path('temp/' . $fileName), 'w');
    fputcsv($file, $columns);
    foreach ($arrayData as $data) {
      fputcsv($file, $data);
    }
    fclose($file);
    return redirect()->route('dashboard');
  }

  public function months()
  {
    $arrayData = Month::all()->map(fn ($m) => [
      'month' => $m->month,
      'user' => $m->user_id ? User::findOrFail($m->user_id)->name : 'unknown',
      'bruto' => $m->bruto,
      'prijevoz' => $m->prijevoz,
      'prehrana' => $m->prehrana,
      'odbitak' => $m->odbitak,
      'prirez' => $m->prirez,
      'minuli' => $m->minuli,
      'prekovremeni' => $m->prekovremeni,
      'stari' => $m->stari,
      'nocni' => $m->nocni,
      'bolovanje' => $m->bolovanje,
      'nagrada' => $m->nagrada,
      'stimulacija' => $m->stimulacija,
      'regres' => $m->regres,
      'bozicnica' => $m->bozicnica,
      'prigodna' => $m->prigodna,
      'sindikat' => $m->sindikat,
      'kredit' => $m->kredit,
    ]);
    $fileName = 'months.csv';
    $columns = array_keys($arrayData[0]);
    $file = fopen(public_path('temp/' . $fileName), 'w');
    fputcsv($file, $columns);
    foreach ($arrayData as $data) {
      fputcsv($file, $data);
    }
    fclose($file);
    return redirect()->route('dashboard');
  }
}
