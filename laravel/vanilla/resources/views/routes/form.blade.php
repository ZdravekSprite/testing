            <!-- data -->
            <x-div>
              <x-label for="data" :value="__('Podaci')" />
              <x-textarea id="data" type="text" name="data">{{$route->data}}</x-textarea>
            </x-div>
