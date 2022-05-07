            <!-- name -->
            <x-div>
              <x-label for="name" :value="__('Naziv')" />
              <x-input id="name" type="text" name="name" :value="$sign->name ?? old('name') ?? ''" required />
            </x-div>
            <!-- description -->
            <x-div>
              <x-label for="description" :value="__('Opis')" />
              <x-input id="description" type="text" name="description" :value="$sign->description ?? old('description') ?? ''" />
            </x-div>
            <!-- svg -->
            <x-div>
              <x-label for="svg" :value="__('Svg')" />
              <x-textarea id="svg" type="text" name="svg">{{$sign->svg}}</x-textarea>
            </x-div>
