<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Binance') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          <p>Index!</p>
          <p><a class="px-6" href="{{ route('binance.create') }}" title="New">Create</a></p>
          <p><a class="px-6" href="{{ route('binance.edit') }}" title="Edit">Edit</a></p>
          <p><a class="px-6" href="{{ route('binance.show') }}" title="Show">Show</a></p>
          <p><a class="px-6" href="{{ route('binance.test') }}" title="Test">Test</a></p>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>