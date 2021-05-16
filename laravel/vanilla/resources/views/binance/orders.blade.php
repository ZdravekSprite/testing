<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Orders') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-gray-100 border-b border-gray-200">
          <div id="app">
            <table class="table-auto w-full">
              <thead>
                <tr>
                  @foreach($simbols as $coin => $data)
                  <th>{{$coin}}</th>
                  @endforeach
                </tr>
              </thead>
              <tbody class="text-sm">
                @foreach([9,8,7,6,5,4,3,2,1,0] as $n)
                <tr>
                  @foreach($simbols as $coin => $data)
                  <td>{{$data['up'][$n]}} {{$data['busd10up'][$n]}} {{$data['up'][$n]*$data['busd10up'][$n]}}</td>
                  @endforeach
                </tr>
                @endforeach
                <tr>
                  @foreach($simbols as $coin => $data)
                  <td>{{$data['price']*1}}</td>
                  @endforeach
                </tr>
                @foreach([0,1,2,3,4,5,6,7,8,9] as $n)
                <tr>
                  @foreach($simbols as $coin => $data)
                  <td>{{$data['down'][$n]}} {{$data['busd10down'][$n]}} {{$data['down'][$n]*$data['busd10down'][$n]}}</td>
                  @endforeach
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
