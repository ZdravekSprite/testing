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
            <!-- a -->
            <x-div>
              <x-label for="a" :value="__('a')" />
              <x-input id="a" type="text" name="a" :value="$sign->a ?? old('a') ?? ''" />
            </x-div>
            <!-- b1 -->
            <x-div>
              <x-label for="b1" :value="__('b1')" />
              <x-input id="b1" type="text" name="b1" :value="$sign->b1 ?? old('b1') ?? ''" />
            </x-div>
            <!-- b2 -->
            <x-div>
              <x-label for="b2" :value="__('b2')" />
              <x-input id="b2" type="text" name="b2" :value="$sign->b2 ?? old('b2') ?? ''" />
            </x-div>
            <!-- c -->
            <x-div>
              <x-label for="c" :value="__('c')" />
              <x-input id="c" type="text" name="c" :value="$sign->c ?? old('c') ?? ''" />
            </x-div>
            <!-- svg_type -->
            <x-div>
              <x-label for="svg_type" :value="__('svg_type')" />
              <x-input id="svg_type" type="text" name="svg_type" :value="$sign->svg_type ?? old('svg_type') ?? ''" />
            </x-div>
            <!-- svg_start_transform -->
            <x-div>
              <x-label for="svg_start_transform" :value="__('svg_start_transform')" />
              <x-input id="svg_start_transform" type="text" name="svg_start_transform" :value="$sign->svg_start_transform ?? old('svg_start_transform') ?? ''" />
            </x-div>
            <!-- svg_start -->
            <x-div>
              <x-label for="svg_start" :value="__('svg_start')" />
              <x-input id="svg_start" type="text" name="svg_start" :value="$sign->svg_start ?? old('svg_start') ?? ''" />
            </x-div>
            <!-- svg -->
            <x-div>
              <x-label for="svg" :value="__('Svg')" />
              <x-textarea id="svg" type="text" name="svg">{{$sign->svg}}</x-textarea>
            </x-div>
            <!-- svg_end_transform -->
            <x-div>
              <x-label for="svg_end_transform" :value="__('svg_end_transform')" />
              <x-input id="svg_end_transform" type="text" name="svg_end_transform" :value="$sign->svg_end_transform ?? old('svg_end_transform') ?? ''" />
            </x-div>
            <!-- svg_end -->
            <x-div>
              <x-label for="svg_end" :value="__('svg_end')" />
              <x-input id="svg_end" type="text" name="svg_end" :value="$sign->svg_end ?? old('svg_end') ?? ''" />
            </x-div>
