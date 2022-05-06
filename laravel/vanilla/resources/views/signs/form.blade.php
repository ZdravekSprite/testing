            <!-- name -->
            <x-div>
              @if(!$sign->name)
              <x-label for="name" :value="__('Naziv')" />
              <x-input id="name" type="text" name="name" :value="$sign->name ?? old('name') ?? ''" required />
              @endif
            </x-div>
            <!-- description -->
            <x-div>
              <x-label for="description" :value="__('Opis')" />
              <x-input id="description" type="text" name="description" :value="$sign->description ?? old('description') ?? ''" />
            </x-div>
            <!-- svg -->
            <x-div>
              <x-label for="svg" :value="__('Svg')" />
              <x-textarea id="svg" name="svg" :value="$sign->svg ?? old('svg') ?? ''"></x-textarea>
            </x-div>
