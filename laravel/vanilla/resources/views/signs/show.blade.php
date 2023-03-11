<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      <a class="float-left px-6" href="{{ route('signs.index') }}" title="Lista"><<</a>
      <span class="float-left px-6">Znak: {{ $sign->name }}!</span>
      @hasrole('admin')
      <a class="float-left px-6" href="{{ route('signs.edit', $sign) }}" title="Izmjeni">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-pen" viewBox="0 0 16 16">
          <path d="M13.498.795l.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z" />
        </svg>
      </a>

      <a class="float-right px-6" style="color:red" href="{{ route('signs.destroy', $sign) }}" onclick="event.preventDefault(); if(confirm('Are you sure?')) document.getElementById('delete-form-{{ $sign->id }}').submit();" title="Izbriši">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
          <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
          <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
        </svg>
      </a>
      <form id="delete-form-{{ $sign->id }}" action="{{ route('signs.destroy', $sign) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
      </form>
      @endhasrole
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          <div class="mb-12">
            <p>description: {{ $sign->description }}</p>
            <p>a: {{ $sign->a }}</p>
            <p>b1: {{ $sign->b1 }}</p>
            <p>b2: {{ $sign->b2 }}</p>
            <p>c: {{ $sign->c }}</p>
            @hasrole('user')
            <p>svg_type: {{ $sign->svg_type }}</p>
            <p>svg_start_fill: {{ $sign->svg_start_fill }}</p>
            <p>svg_start_transfrm: {{ $sign->svg_start_transfrm }}</p>
            <p>svg_start: {{ $sign->svg_start }}</p>
            <p>sign->svg:</p>
            <pre>{{ $sign->svg }}</pre>
            <p>svg_end_fill: {{ $sign->svg_end_fill }}</p>
            <p>svg_end_transfrm {{ $sign->svg_end_transfrm }}</p>
            <p>svg_end {{ $sign->svg_end }}</p>
            @endhasrole
            <img src="{{ url('/') . '/gif/' . $sign->name }}" alt="{{ $sign->description }}" width="100" height="100">
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="720px" height="720px" viewBox="0 0 720 720">
            {!! $sign->svg_all() !!}
            </svg>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>