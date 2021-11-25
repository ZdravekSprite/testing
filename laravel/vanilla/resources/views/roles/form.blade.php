            <!-- description -->
            <x-div>
              <x-label for="description" :value="__('Opis')" />
              <x-input id="description" type="text" name="description" value="{{ $role->description ?? old('description') ?? ''}}" required autofocus class="block mt-1 w-full" />
            </x-div>
