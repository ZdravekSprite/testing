            <!-- name -->
            <x-div>
              @if(!$article->name)
              <x-label for="name" :value="__('Naziv')" />
              <x-input id="name" type="text" name="name" :value="$article->name ?? old('name') ?? ''" required />
              @endif
            </x-div>
            @if($article->data && count($article->data) > 0)
            @foreach($article->data as $key => $data)
            <!-- link -->
            <x-div>
              <x-label for="link{{$key}}" :value="__('Link '.($key+1))" />
              <x-input id="link{{$key}}" type="text" name="data[{{$key}}][link]" value="{{$data->link}}" />
            </x-div>
            <!-- price -->
            <x-div>
              <x-label for="price{{$key}}" :value="__('Price '.($key+1))" />
              <x-input id="price{{$key}}" type="text" name="data[{{$key}}][price]" value="{{$data->price}}" />
            </x-div>
            <!-- description -->
            <x-div>
              <x-label for="description{{$key}}" :value="__('Opis '.($key+1))" />
              <x-textarea id="description{{$key}}" name="data[{{$key}}][description]">{{$data->description}}</x-textarea>
            </x-div>
            @endforeach
            <!-- link -->
            <x-div>
              <x-label for="link" :value="__('Link')" />
              <x-input id="link" type="text" name="data[{{count($article->data)}}][link]" :value="old('link') ?? ''" />
            </x-div>
            <!-- price -->
            <x-div>
              <x-label for="price" :value="__('Price')" />
              <x-input id="price" type="text" name="data[{{count($article->data)}}][price]" :value="old('price') ?? ''" />
            </x-div>
            <!-- description -->
            <x-div>
              <x-label for="description" :value="__('Opis')" />
              <x-textarea id="description" name="data[{{count($article->data)}}][description]" :value="old('description') ?? ''"></x-textarea>
            </x-div>
            @else
            <!-- link -->
            <x-div>
              <x-label for="link" :value="__('Link')" />
              <x-input id="link" type="text" name="data[0][link]" :value="old('link') ?? ''" />
            </x-div>
            <!-- price -->
            <x-div>
              <x-label for="price" :value="__('Price')" />
              <x-input id="price" type="text" name="data[0][price]" :value="old('price') ?? ''" />
            </x-div>
            <!-- description -->
            <x-div>
              <x-label for="description" :value="__('Opis')" />
              <x-textarea id="description" name="data[0][description]" :value="old('description') ?? ''"></x-textarea>
            </x-div>
            @endif