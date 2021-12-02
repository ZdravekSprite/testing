<x-guest-layout>
  <x-auth-card>
    <x-slot name="logo">
      <a href="/">
        <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
      </a>
    </x-slot>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Validation Errors -->
    <x-auth-validation-errors class="mb-4" :errors="$errors" />

    <form method="POST" action="{{ route('login') }}">
      @csrf

      <!-- Email Address -->
      <div>
        <x-label for="email" :value="__('Email')" />
        <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
      </div>

      <!-- Password -->
      <div class="mt-4">
        <x-label for="password" :value="__('Password')" />
        <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
      </div>

      <!-- Remember Me -->
      <div class="block mt-4">
        <label for="remember_me" class="inline-flex items-center">
          <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="remember">
          <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
        </label>
      </div>

      <div class="flex items-center justify-end mt-4">
        @if (Route::has('register'))
        <a class="ml-2 underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('register') }}">
          {{ __('Register?') }}
        </a>
        @endif
        @if (Route::has('password.request'))
        <a class="ml-2 underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
          {{ __('Forgot your password?') }}
        </a>
        @endif
        <x-button class="ml-3">
          {{ __('Log in') }}
        </x-button>
      </div>
    </form>
    <div class="flex justify-between items-center mt-3">
      <hr class="w-full"> <span class="p-2 text-gray-400 mb-1">OR</span>
      <hr class="w-full">
    </div>
    <div class="flex items-center justify-end mt-4">
      <a href="login/facebook" class="inline-flex items-center px-4 py-2 space-x-1 border border-transparent rounded-md text-sm text-gray-600 hover:text-gray-900">
        <svg class="centerHV" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 267 267" width="25" height="25">
          <g fill="white">
            <path id="Blue_1_" fill="#3C5A99" d="M248.082,262.307c7.854,0,14.223-6.369,14.223-14.225V18.812	c0-7.857-6.368-14.224-14.223-14.224H18.812c-7.857,0-14.224,6.367-14.224,14.224v229.27c0,7.855,6.366,14.225,14.224,14.225 H248.082z" />
            <path id="f" fill="#FFFFFF" d="M182.409,262.307v-99.803h33.499l5.016-38.895h-38.515V98.777c0-11.261,3.127-18.935,19.275-18.935 l20.596-0.009V45.045c-3.562-0.474-15.788-1.533-30.012-1.533c-29.695,0-50.025,18.126-50.025,51.413v28.684h-33.585v38.895h33.585	v99.803H182.409z" />
          </g>
        </svg>
        <strong>{{ __('Login') }}</strong>
        <span>{{ __('with') }}</span>
        <strong>Facebook</strong>
      </a>
      <a href="login/google" class="inline-flex items-center px-4 py-2 space-x-1 border border-transparent rounded-md text-sm text-gray-600 hover:text-gray-900">
        <svg class="centerHV" width="25px" height="25px" viewBox="8 8 30 30" xmlns="http://www.w3.org/2000/svg">
          <defs>
            <filter x="-50%" y="-50%" width="200%" height="200%" filterUnits="objectBoundingBox" id="filter-1">
              <feOffset dx="0" dy="1" in="SourceAlpha" result="shadowOffsetOuter1"></feOffset>
              <feGaussianBlur stdDeviation="0.5" in="shadowOffsetOuter1" result="shadowBlurOuter1"></feGaussianBlur>
              <feColorMatrix values="0 0 0 0 0   0 0 0 0 0   0 0 0 0 0  0 0 0 0.168 0" in="shadowBlurOuter1" type="matrix" result="shadowMatrixOuter1"></feColorMatrix>
              <feOffset dx="0" dy="0" in="SourceAlpha" result="shadowOffsetOuter2"></feOffset>
              <feGaussianBlur stdDeviation="0.5" in="shadowOffsetOuter2" result="shadowBlurOuter2"></feGaussianBlur>
              <feColorMatrix values="0 0 0 0 0   0 0 0 0 0   0 0 0 0 0  0 0 0 0.084 0" in="shadowBlurOuter2" type="matrix" result="shadowMatrixOuter2"></feColorMatrix>
              <feMerge>
                <feMergeNode in="shadowMatrixOuter1"></feMergeNode>
                <feMergeNode in="shadowMatrixOuter2"></feMergeNode>
                <feMergeNode in="SourceGraphic"></feMergeNode>
              </feMerge>
            </filter>
            <rect id="path-2" x="0" y="0" width="40" height="40" rx="2"></rect>
          </defs>
          <g id="Google-Button" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">
            <g id="9-PATCH" sketch:type="MSArtboardGroup" transform="translate(-608.000000, -160.000000)"></g>
            <g id="btn_google_light_normal" sketch:type="MSArtboardGroup" transform="translate(-1.000000, -1.000000)">
              <g id="button" sketch:type="MSLayerGroup" transform="translate(4.000000, 4.000000)" filter="url(#filter-1)">
                <g id="button-bg">
                  <use fill="#FFFFFF" fill-rule="evenodd" sketch:type="MSShapeGroup" xlink:href="#path-2"></use>
                  <use fill="none" xlink:href="#path-2"></use>
                  <use fill="none" xlink:href="#path-2"></use>
                  <use fill="none" xlink:href="#path-2"></use>
                </g>
              </g>
              <g id="logo_googleg_48dp" sketch:type="MSLayerGroup" transform="translate(15.000000, 15.000000)">
                <path d="M17.64,9.20454545 C17.64,8.56636364 17.5827273,7.95272727 17.4763636,7.36363636 L9,7.36363636 L9,10.845 L13.8436364,10.845 C13.635,11.97 13.0009091,12.9231818 12.0477273,13.5613636 L12.0477273,15.8195455 L14.9563636,15.8195455 C16.6581818,14.2527273 17.64,11.9454545 17.64,9.20454545 L17.64,9.20454545 Z" id="Shape" fill="#4285F4" sketch:type="MSShapeGroup"></path>
                <path d="M9,18 C11.43,18 13.4672727,17.1940909 14.9563636,15.8195455 L12.0477273,13.5613636 C11.2418182,14.1013636 10.2109091,14.4204545 9,14.4204545 C6.65590909,14.4204545 4.67181818,12.8372727 3.96409091,10.71 L0.957272727,10.71 L0.957272727,13.0418182 C2.43818182,15.9831818 5.48181818,18 9,18 L9,18 Z" id="Shape" fill="#34A853" sketch:type="MSShapeGroup"></path>
                <path d="M3.96409091,10.71 C3.78409091,10.17 3.68181818,9.59318182 3.68181818,9 C3.68181818,8.40681818 3.78409091,7.83 3.96409091,7.29 L3.96409091,4.95818182 L0.957272727,4.95818182 C0.347727273,6.17318182 0,7.54772727 0,9 C0,10.4522727 0.347727273,11.8268182 0.957272727,13.0418182 L3.96409091,10.71 L3.96409091,10.71 Z" id="Shape" fill="#FBBC05" sketch:type="MSShapeGroup"></path>
                <path d="M9,3.57954545 C10.3213636,3.57954545 11.5077273,4.03363636 12.4404545,4.92545455 L15.0218182,2.34409091 C13.4631818,0.891818182 11.4259091,0 9,0 C5.48181818,0 2.43818182,2.01681818 0.957272727,4.95818182 L3.96409091,7.29 C4.67181818,5.16272727 6.65590909,3.57954545 9,3.57954545 L9,3.57954545 Z" id="Shape" fill="#EA4335" sketch:type="MSShapeGroup"></path>
                <path d="M0,0 L18,0 L18,18 L0,18 L0,0 Z" id="Shape" sketch:type="MSShapeGroup"></path>
              </g>
              <g id="handles_square" sketch:type="MSLayerGroup"></g>
            </g>
          </g>
        </svg>
        <strong>{{ __('Login') }}</strong>
        <span>{{ __('with') }}</span>
        <strong>Google</strong>
      </a>
    </div>
    @if (null)
    <div class="flex items-center justify-end">
      <a href="login/twitter" class="inline-flex items-center px-4 py-2 space-x-1 border border-transparent rounded-md text-sm text-gray-600 hover:text-gray-900">
        <svg viewBox="0 0 24 24" height="25" width="25">
          <path fill="rgb(29,161,242)" d="M23.643 4.937c-.835.37-1.732.62-2.675.733.962-.576 1.7-1.49 2.048-2.578-.9.534-1.897.922-2.958 1.13-.85-.904-2.06-1.47-3.4-1.47-2.572 0-4.658 2.086-4.658 4.66 0 .364.042.718.12 1.06-3.873-.195-7.304-2.05-9.602-4.868-.4.69-.63 1.49-.63 2.342 0 1.616.823 3.043 2.072 3.878-.764-.025-1.482-.234-2.11-.583v.06c0 2.257 1.605 4.14 3.737 4.568-.392.106-.803.162-1.227.162-.3 0-.593-.028-.877-.082.593 1.85 2.313 3.198 4.352 3.234-1.595 1.25-3.604 1.995-5.786 1.995-.376 0-.747-.022-1.112-.065 2.062 1.323 4.51 2.093 7.14 2.093 8.57 0 13.255-7.098 13.255-13.254 0-.2-.005-.402-.014-.602.91-.658 1.7-1.477 2.323-2.41z" />
        </svg>
        <strong>{{ __('Login') }}</strong>
        <span>{{ __('with') }}</span>
        <strong>Twitter</strong>
      </a>
      <a href="login/linkedin" class="inline-flex items-center px-4 py-2 space-x-1 border border-transparent rounded-md text-sm text-gray-600 hover:text-gray-900">
        <svg viewBox="0 0 34 34" width="25" height="25">
          <path fill="white" d="M30.4,0.8H3.6C2,0.8,0.8,2,0.8,3.6v26.8c0,1.6,1.3,2.8,2.8,2.8h26.8c1.6,0,2.8-1.3,2.8-2.8V3.6 C33.2,2,32,0.8,30.4,0.8z" />
          <path d="M31.5,0h-29C1.1,0,0,1.1,0,2.5v29.1C0,32.9,1.1,34,2.5,34h29c1.4,0,2.5-1.1,2.5-2.5V2.5C34,1.1,32.9,0,31.5,0 M10.1,29H5 V12.7h5V29z M7.6,10.5c-1.6,0-2.9-1.3-2.9-2.9c0-1.6,1.3-2.9,2.9-2.9c1.6,0,2.9,1.3,2.9,2.9C10.5,9.2,9.2,10.5,7.6,10.5 M29,29h-5 v-7.9c0-1.9,0-4.3-2.6-4.3c-2.6,0-3,2-3,4.2v8h-5V12.7h4.8V15h0.1c0.7-1.3,2.3-2.6,4.8-2.6c5.1,0,6,3.4,6,7.7V29z" />
        </svg>
        <strong>{{ __('Login') }}</strong>
        <span>{{ __('with') }}</span>
        <strong>LinkedIn</strong>
      </a>
    </div>
    <div class="flex items-center justify-center">
      <a href="login/github" class="inline-flex items-center px-4 py-2 space-x-1 border border-transparent rounded-md text-sm text-gray-600 hover:text-gray-900">
        <svg viewBox="0 0 16 16" height="25" width="25">
          <path fill-rule="evenodd" d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0016 8c0-4.42-3.58-8-8-8z" />
        </svg>
        <strong>{{ __('Login') }}</strong>
        <span>{{ __('with') }}</span>
        <strong>GitHub</strong>
      </a>
    </div>
    @endif
  </x-auth-card>
</x-guest-layout>
