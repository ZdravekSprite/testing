<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Dust') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          <table class="table-auto w-full">
            <thead>
              <tr>
                  @if(count($sortedData) > 0)
                  <th>{{count($sortedData)}}</th>
                  <th>Asset</th>
                  <th>amount</th>
                  <th>BNB</th>
                  <th>fee</th>
                  @else
                  No dust found
                  @endif
              </tr>
            </thead>
            <tbody class="text-sm">
              @foreach($sortedData as $key => $value)
              <tr>
                <td>{{$value->operateTime}}</td>
                <td>{{$value->fromAsset}}</td>
                <td>{{$value->amount}}</td>
                <td>{{$value->transferedAmount}}</td>
                <td>{{$value->serviceChargeAmount}}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>