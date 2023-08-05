            <!-- description -->
            <x-div>
              <x-label for="description" :value="__('Opis')" />
              <x-input id="description" type="text" name="description" value="{{ $role->description ?? old('description') ?? ''}}" autofocus />
            </x-div>
