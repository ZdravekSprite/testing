            <!-- no01 -->
            <x-div>
              <x-label for="no01" :value="__('no01')" />
              <x-input id="no01" type="number" name="no01" value="{{$draw->no01 ? $draw->no01 : old('no01')?? '1'}}" required />
            </x-div>
            <!-- no02 -->
            <x-div>
              <x-label for="no02" :value="__('no02')" />
              <x-input id="no02" type="number" name="no02" value="{{$draw->no02 ? $draw->no02 : old('no02')?? '2'}}" required />
            </x-div>
            <!-- no03 -->
            <x-div>
              <x-label for="no03" :value="__('no03')" />
              <x-input id="no03" type="number" name="no03" value="{{$draw->no03 ? $draw->no03 : old('no03')?? '3'}}" required />
            </x-div>
            <!-- no04 -->
            <x-div>
              <x-label for="no04" :value="__('no04')" />
              <x-input id="no04" type="number" name="no04" value="{{$draw->no04 ? $draw->no04 : old('no04')?? '4'}}" required />
            </x-div>
            <!-- no05 -->
            <x-div>
              <x-label for="no05" :value="__('no05')" />
              <x-input id="no05" type="number" name="no05" value="{{$draw->no05 ? $draw->no05 : old('no05')?? '5'}}" required />
            </x-div>
            <!-- bo01 -->
            <x-div>
              <x-label for="bo01" :value="__('bo01')" />
              <x-input id="bo01" type="number" name="bo01" value="{{$draw->bo01 ? $draw->bo01 : old('bo01')?? '1'}}" required />
            </x-div>
            <!-- bo02 -->
            <x-div>
              <x-label for="bo02" :value="__('bo02')" />
              <x-input id="bo02" type="number" name="bo02" value="{{$draw->bo02 ? $draw->bo02 : old('bo02')?? '2'}}" required />
            </x-div>
