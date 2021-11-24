<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Manage korisnika!')}}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          {{ __('Manage') .' '. $user->name }}
          <!-- Validation Errors -->
          <x-auth-validation-errors class="mb-4" :errors="$errors" />

          <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
            @csrf
            @method('PUT')

            <!-- role -->
            @foreach ($roles as $role)
            <div class="mt-4">
              <x-label for="{{ $role->name }}" value="{{$role->name}}" />
              <x-input id="{{ $role->name }}" class="block mt-1 w-full" type="checkbox" name="roles[]" value="{{ $role->id }}" :checked="$user->hasAnyRole($role->name) ? 'checked' : null" />
            </div>
            @endforeach

            <div class="flex items-center justify-end mt-4">
              <x-button class="ml-4">
                {{ __('Spremi') }}
              </x-button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
