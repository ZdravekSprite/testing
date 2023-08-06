            <!-- date -->
            <x-div>
              <x-label for="date" :value="__('Datum')" />
              <x-input id="date" type="date" name="date" :value="$holiday->date ? $holiday->date->format('Y-m-d') : old('date')" required autofocus />
            </x-div>

            <!-- name -->
            <x-div>
              <x-label for="name" :value="__('Naziv')" />
              <x-input id="name" type="text" name="name" :value="$holiday->name ?? old('name') ?? ''" required />
            </x-div>
