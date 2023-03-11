            <!-- BINANCE_API_KEY -->
            <div class="mt-4">
              <x-label for="bkey" :value="__('BINANCE_API_KEY')" />
              <x-input id="bkey" class="block mt-1 w-full" type="text" name="bkey" value="{{ $binance ? $binance->BINANCE_API_KEY : old('bkey')?? null}}" />
            </div>
            <!-- BINANCE_API_SECRET -->
            <div class="mt-4">
              <x-label for="bsecret" :value="__('BINANCE_API_SECRET')" />
              <x-input id="bsecret" class="block mt-1 w-full" type="text" name="bsecret" value="{{ $binance ? $binance->BINANCE_API_SECRET : old('bsecret')?? null}}" />
            </div>