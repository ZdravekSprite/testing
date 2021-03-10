## Laravel Socialite

```bash
composer require laravel/socialite
composer require doctrine/dbal
php artisan make:migration add_socialite_to_users_table --table=users
```
### database\migrations\2021_03_09_090727_add_socialite_to_users_table.php
```php
  public function up()
  {
    Schema::table('users', function (Blueprint $table) {
      $table->string('name')->nullable()->change();
      $table->string('password')->nullable()->change();
      $table->string('facebook_id')->nullable();
      $table->string('twitter_id')->nullable();
      $table->string('linkedin_id')->nullable();
      $table->string('google_id')->nullable();
      $table->string('github_id')->nullable();
      $table->string('avatar')->nullable();
      $table->string('facebook_avatar')->nullable();
      $table->string('twitter_avatar')->nullable();
      $table->string('linkedin_avatar')->nullable();
      $table->string('google_avatar')->nullable();
      $table->string('github_avatar')->nullable();
    });
  }
  public function down()
  {
    Schema::table('users', function (Blueprint $table) {
      $table->string('name')->change();
      $table->string('password')->change();
      $table->dropColumn('facebook_id', 'twitter_id', 'linkedin_id', 'google_id', 'github_id');
      $table->dropColumn('avatar', 'facebook_avatar', 'twitter_avatar', 'linkedin_avatar', 'google_avatar', 'github_avatar');
    });
  }
```
```bash
php artisan migrate
```
## https://developers.facebook.com/
> - /login/facebook/callback
## https://developer.twitter.com/
> - /login/twitter/callback
## https://www.linkedin.com/developers/
> - /login/linkedin/callback
## https://console.developers.google.com/
> - /login/google/callback
## https://github.com/settings/apps
> - /login/github/callback
### .env
```ts
APP_LOCALE=hr

FACEBOOK_CLIENT_ID=FACEBOOK_CLIENT_ID
FACEBOOK_CLIENT_SECRET=FACEBOOK_CLIENT_SECRET
FACEBOOK_REDIRECT="${APP_URL}/login/facebook/callback"

TWITTER_CLIENT_ID=TWITTER_CLIENT_ID
TWITTER_CLIENT_SECRET=TWITTER_CLIENT_SECRET
TWITTER_REDIRECT="${APP_URL}/login/twitter/callback"

LINKEDIN_CLIENT_ID=LINKEDIN_CLIENT_ID
LINKEDIN_CLIENT_SECRET=LINKEDIN_CLIENT_SECRET
LINKEDIN_REDIRECT="${APP_URL}/login/linkedin/callback"

GOOGLE_CLIENT_ID=GOOGLE_CLIENT_ID
GOOGLE_CLIENT_SECRET=GOOGLE_CLIENT_SECRET
GOOGLE_REDIRECT="${APP_URL}/login/google/callback"

GITHUB_CLIENT_ID=GITHUB_CLIENT_ID
GITHUB_CLIENT_SECRET=GITHUB_CLIENT_SECRET
GITHUB_REDIRECT="${APP_URL}/login/github/callback" 
```
## config\services.php
```php
  'facebook' => [
    'client_id' => env('FACEBOOK_CLIENT_ID'),
    'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
    'redirect' => env('FACEBOOK_REDIRECT'),
  ],

  'twitter' => [
    'client_id' => env('TWITTER_CLIENT_ID'),
    'client_secret' => env('TWITTER_CLIENT_SECRET'),
    'redirect' => env('TWITTER_REDIRECT'),
  ],

  'linkedin' => [
    'client_id' => env('LINKEDIN_CLIENT_ID'),
    'client_secret' => env('LINKEDIN_CLIENT_SECRET'),
    'redirect' => env('LINKEDIN_REDIRECT'),
  ],

  'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT'),
  ],

  'github' => [
    'client_id' => env('GITHUB_CLIENT_ID'),
    'client_secret' => env('GITHUB_CLIENT_SECRET'),
    'redirect' => env('GITHUB_REDIRECT'),
  ],
```
## routes\web.php
```php
Route::get('login/{provider}', function ($provider) {
  return Socialite::driver($provider)->redirect();
})->name('{provider}Login');
Route::get('login/{provider}/callback', function ($provider) {
  $social_user = Socialite::driver($provider)->user();
  // $user->token
  $user = User::firstOrCreate([
    'email' => $social_user->getEmail(),
  ]);
  if (!$user->name) {
    $user->name = $social_user->getName();
  }
  if (!$user[$provider . "_id"]) {
    $user[$provider . "_id"] = $social_user->getId();
  }
  if ($social_user->getAvatar()) {
    if (!$user->avatar) {
      $user->avatar = $social_user->getAvatar();
    }
    if (!$user[$provider . "_avatar"]) {
      $user[$provider . "_avatar"] = $social_user->getAvatar();
    }
  }
  $user->save();
  Auth::Login($user, true);
  return redirect(route('home'));
})->name('{provider}Callback');
/*
Route::get('login/{provider}', 'Auth\LoginController@redirectToProvider')->name('{provider}Login');
Route::get('login/{provider}/callback', 'Auth\LoginController@handleProviderCallback')->name('{provider}Callback');
*/
```
## resources\views\auth\login.blade.php
```php
    <div class="flex justify-between items-center mt-3">
      <hr class="w-full"> <span class="p-2 text-gray-400 mb-1">OR</span>
      <hr class="w-full">
    </div>
    <div class="flex items-center justify-end mt-4">
      <a href="login/facebook" class="inline-flex items-center px-4 py-2 space-x-1 border border-transparent rounded-md text-sm text-gray-600 hover:text-gray-900">
        <svg class="centerHV" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 267 267" width="25" height="25">
          <g fill="white">
            <path id="Blue_1_" fill="#3C5A99" d="M248.082,262.307c7.854,0,14.223-6.369,14.223-14.225V18.812
	c0-7.857-6.368-14.224-14.223-14.224H18.812c-7.857,0-14.224,6.367-14.224,14.224v229.27c0,7.855,6.366,14.225,14.224,14.225
	H248.082z" />
            <path id="f" fill="#FFFFFF" d="M182.409,262.307v-99.803h33.499l5.016-38.895h-38.515V98.777c0-11.261,3.127-18.935,19.275-18.935
	l20.596-0.009V45.045c-3.562-0.474-15.788-1.533-30.012-1.533c-29.695,0-50.025,18.126-50.025,51.413v28.684h-33.585v38.895h33.585
	v99.803H182.409z" />
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
```
```
git add .
git commit -am "Laravel Socialite v0.6 [laravel]"
```