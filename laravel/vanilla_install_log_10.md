# Laravel Vue
```bash
npm install
npm install vue
npm install vue-template-compiler vue-loader@^15.9.5 --save-dev --legacy-peer-deps
```
### webpack.mix.js
```ts
mix.js('resources/js/app.js', 'public/js').vue()
  .postCss('resources/css/app.css', 'public/css', [
    require('postcss-import'),
    require('tailwindcss'),
    require('autoprefixer'),
  ]);
```
### resources\js\app.js
```ts
import Vue from 'vue';
Vue.component('example-component', require('./components/ExampleComponent.vue').default);
const app = new Vue({
  el: '#app',
});
```
### resources\views\dashboard.blade.php
```php
          <div id="app">
            <example-component></example-component>
          </div>
```
### resources\js\components\ExampleComponent.vue
```ts
<template>
  <div>I'm an example component.</div>
</template>

<script>
export default {
  mounted() {
    console.log("Component mounted.");
  },
};
</script>
```
```bash
npm run dev
php artisan serve
git add .
git commit -am "Laravel Vue v0.10a [laravel]"
```
# Laravel Chat
```bash
composer require pusher/pusher-php-server
npm install --save laravel-echo pusher-js
```
## config\app.php
174
```php
    App\Providers\BroadcastServiceProvider::class,
```
## config\broadcasting.php
18
```php
    'default' => env('BROADCAST_DRIVER', 'pusher'),
```
## .env
```ts
18 BROADCAST_DRIVER=pusher
42-45
PUSHER_APP_ID=PUSHER_APP_ID
PUSHER_APP_KEY=PUSHER_APP_KEY
PUSHER_APP_SECRET=PUSHER_APP_SECRET
PUSHER_APP_CLUSTER=PUSHER_APP_CLUSTER
```
```bash
php artisan make:model Chat -a
```
### database\migrations\2021_03_18_105541_create_chats_table.php
```php
  public function up()
  {
    Schema::create('chats', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('user_id');
      $table->text('message');
      $table->timestamps();
      $table->foreign('user_id')->references('id')->on('users');
    });
  }
```
```bash
php artisan migrate
```
### app\Models\Chat.php
```php
  protected $guarded = [];

  public function user()
  {
    return $this->belongsTo(User::class);
  }
```
### app\Models\User.php
```php
  public function messages()
  {
    return $this->hasMany(Chat::class);
  }
```
### routes\web.php
```php
use App\Http\Controllers\ChatController;
Route::get('/chat', [ChatController::class, 'index'])->name('chat');
Route::get('/messages', [ChatController::class, 'fetchAllMessages']);
Route::post('/messages', [ChatController::class, 'sendMessage']);
```
```bash
php artisan make:event ChatEvent
```
## app\Events\ChatEvent.php
```php
use App\Models\Chat;
  public function __construct(Chat $chat)
  {
    $this->chat = $chat;
  }
  public function broadcastOn()
  {
    return new PresenceChannel('chat');
  }
```
## app\Http\Controllers\ChatController.php
```php
use App\Events\ChatEvent;
  public function __construct()
  {
    $this->middleware('auth');
  }
  public function index()
  {
    return view('chat');
  }

  public function fetchAllMessages()
  {
    return Chat::with('user')->get();
  }

  public function sendMessage(Request $request)
  {
    $chat = auth()->user()->messages()->create([
      'message' => $request->message
    ]);

    broadcast(new ChatEvent($chat->load('user')))->toOthers();

    return ['status' => 'success'];
  }
```
### resources\views\chat.blade.php
```php
<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Chat') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-gray-100 border-b border-gray-200">
          <div id="app">
            <chat-component :user="{{ auth()->user() }}"></chat-component>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
```
### resources\js\components\ChatComponent.vue
```ts
<template>
  <div class="row">
    <div class="col-8">
      <div class="card card-default">
        <div class="card-header">Messages</div>
        <div class="card-body p-0">
          <ul
            class="list-unstyled"
            style="height: 300px; overflow-y: scroll"
            v-chat-scroll
          >
            <li class="p-2" v-for="(message, index) in messages" :key="index">
              <img width="20" height="20" v-bind:src="message.user.avatar" />
              <strong>{{ message.user.name }}</strong>
              {{ message.message }}
            </li>
          </ul>
        </div>

        <input
          @keydown="sendTypingEvent"
          @keyup.enter="sendMessage"
          v-model="newMessage"
          type="text"
          name="message"
          placeholder="Enter your message..."
          class="form-control"
        />
      </div>
      <span class="text-muted" v-if="activeUser"
        >{{ activeUser.name }} is typing...</span
      >
    </div>

    <div class="col-4">
      <div class="card card-default">
        <div class="card-header">Active Users</div>
        <div class="card-body">
          <ul>
            <li class="py-2" v-for="(user, index) in users" :key="index">
              <img width="20" height="20" v-bind:src="user.avatar" />
              {{ user.name }}
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["user"],
  data() {
    return {
      messages: [],
      newMessage: "",
      users: [],
      activeUser: false,
      typingTimer: false,
    };
  },
  created() {
    this.fetchMessages();
    Echo.join("chat")
      .here((user) => {
        this.users = user;
      })
      .joining((user) => {
        this.users.push(user);
      })
      .leaving((user) => {
        this.users = this.users.filter((u) => u.id != user.id);
      })
      .listen("ChatEvent", (event) => {
        this.messages.push(event.chat);
      })
      .listenForWhisper("typing", (user) => {
        this.activeUser = user;
        if (this.typingTimer) {
          clearTimeout(this.typingTimer);
        }
        this.typingTimer = setTimeout(() => {
          this.activeUser = false;
          this.fetchMessages();
        }, 1000);
      });
  },
  methods: {
    fetchMessages() {
      axios.get("messages").then((response) => {
        this.messages = response.data;
      });
    },
    sendMessage() {
      this.messages.push({
        user: this.user,
        message: this.newMessage,
      });
      axios.post("messages", { message: this.newMessage });
      this.newMessage = "";
    },
    sendTypingEvent() {
      Echo.join("chat").whisper("typing", this.user);
      console.log(this.user.name + " is typing now");
    },
  },
};
</script>
```
```bash
npm install --save vue-chat-scroll
```
### resources\js\app.js
```ts
const files = require.context('./', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

import VueChatScroll from 'vue-chat-scroll'
Vue.use(VueChatScroll)
```
## resources\js\bootstrap.js
```ts
import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

window.Echo = new Echo({
  broadcaster: 'pusher',
  key: process.env.MIX_PUSHER_APP_KEY,
  cluster: process.env.MIX_PUSHER_APP_CLUSTER,
  forceTLS: true
});
```
## routes\channels.php
```php
Broadcast::channel('chat', function ($user) {
  return $user;
});
```
### resources\views\layouts\app.blade.php
```php
  <meta name="userId" content="{{ Auth::check() ? Auth::user()->id : '' }}">
```
```bash
npm run dev
php artisan serve
git add .
git commit -am "Laravel Chat v0.10b [laravel]"
```
### resources\views\layouts\navigation.blade.php
```php
        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
          <x-nav-link :href="route('chat')" :active="request()->routeIs('chat')">
            {{ __('Chat') }}
          </x-nav-link>
        </div>
```
### app\Models\Chat.php
```php
  protected $casts = [
    'created_at' => 'datetime:Y-m-d H:i',
  ];
```
### app\Models\User.php
```php
  public function getAvatarAttribute($value)
  {
    if (is_null($value)) {
      $value = asset('img/avatar.jpg');
    }

    return $value;
  }
```
### resources\views\admin\users\index.blade.php
```php
                <th class="flex items-center pr-10"><img class="rounded-full shadow-xl" style="box-shadow: " width="20" height="20" src="{{$user->avatar}}" />{{$user->name}}</th>
```
### resources\js\components\ChatComponent.vue
```ts
<template>
  <div class="grid grid-cols-4 grid-flow-col gap-2 h-auto rounded shadow-2xl">
    <div class="col-span-3">
      <nav
        class="w-full h-10 bg-gray-900 rounded-tr rounded-tl flex justify-between items-center"
      >
        <div class="flex justify-center items-center">
          <span class="text-xs font-medium text-gray-300 ml-1">Messages</span>
        </div>
        <div class="flex items-center">
          <span class="text-xs font-medium text-gray-300 ml-1">...</span>
        </div>
      </nav>
      <div class="overflow-auto px-1 py-1">
        <ul
          class="list-unstyled"
          style="height: 300px; overflow-y: scroll"
          v-chat-scroll
        >
          <li
            class="flex items-center pr-10"
            v-for="(message, index) in messages"
            :key="index"
          >
            <img
              class="rounded-full shadow-xl"
              style="box-shadow: "
              width="20"
              height="20"
              v-bind:src="message.user.avatar"
            />
            <span style="font-size: 12px">{{ message.user.name }}</span>
            <span
              class="flex ml-1 h-auto bg-gray-900 text-gray-200 text-xs font-normal rounded-sm px-1 p-1 items-end"
              style="font-size: 12px"
            >
              {{ message.message }}
              <span class="text-gray-400 pl-1" style="font-size: 10px">@
                {{ message.created_at }}
              </span>
            </span>
          </li>
        </ul>
      </div>
      <div class="flex justify-between items-center p-1">
        <div class="relative">
          <input
            @keydown="sendTypingEvent"
            @keyup.enter="sendMessage"
            v-model="newMessage"
            type="text"
            name="message"
            placeholder="Enter your message..."
            class="rounded-full pl-6 pr-12 py-2 focus:outline-none h-auto placeholder-gray-100 bg-gray-900 text-white"
            style="font-size: 11px; width: 250px"
          />
          <span class="text-muted" v-if="activeUser"
            >{{ activeUser.name }} is typing...</span
          >
        </div>
      </div>
    </div>

    <div class="col-span-1">
      <nav
        class="w-full h-10 bg-gray-900 rounded-tr rounded-tl flex justify-between items-center"
      >
        <div class="flex justify-center items-center">
          <span class="text-xs font-medium text-gray-300 ml-1">Active Users</span>
        </div>
        <div class="flex items-center">
          <span class="text-xs font-medium text-gray-300 ml-1">...</span>
        </div>
      </nav>
      <div class="overflow-auto px-1 py-1">
        <ul>
          <li
            class="flex items-center pr-10"
            v-for="(user, index) in users"
            :key="index"
          >
            <img
              class="rounded-full shadow-xl"
              style="box-shadow: "
              width="20"
              height="20"
              v-bind:src="user.avatar"
            />
            <span style="font-size: 12px">{{ user.name }}</span>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>
```
```bash
php artisan serve
npm run watch
git add .
git commit -am "Laravel Chat v0.10e [laravel]"
git add .
git commit -am "čišćenje [laravel]"
```
#To-do
srediti da kad nema vue da ne javlja da nemože naći app

```
php artisan make:migration add_dopust_to_days_table --table=days
```
### database\migrations\2021_07_11_102248_add_dopust_to_days_table.php
```
  public function up()
  {
    Schema::table('days', function (Blueprint $table) {
      $table->boolean('dopust')
        ->after('go')
        ->default(false);
    });
  }
  public function down()
  {
    Schema::table('days', function (Blueprint $table) {
      $table->dropColumn('dopust');
    });
  }
```
```
php artisan migrate
```
### resources\views\days\index.blade.php
```
                  <div class="w-full rounded-md relative {{$day->sick ? 'bg-red' : ($day->go ? 'bg-green' : ($day->dopust ? 'bg-teal' : 'bg-indigo'))}}-{{$day->date->format('D') == 'Sun' ? '300' : '100'}}" style="min-height: 18px;" title={{$day->date->format('d.m.Y')}}>
```
### resources\views\days\show.blade.php
```
          @if($day->dopust)
          <p>Bio sam na plaćenom dopustu</p>
          @endif
```
### resources\views\days\create.blade.php
```
            <!-- dopust -->
            <div class="mt-4">
              <x-label for="dopust" :value="__('Dopust')" />
              <input onclick="ShowHideDiv(this)" id="dopust" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="checkbox" name="dopust" />
              <p>Da li ste taj dan dobili plačeni dopust? Ako je godišnji onda bi ostale vrijednosti trebale biti 00:00</p>
              <p>Dobio za smrt u obitelji 2 dana plačena <a href="mailto:zdravek.sprite@gmail.com">mail zdravek.sprite@gmail.com</a></p>
            </div>

```
### resources\views\days\edit.blade.php
```
            <!-- dopust -->
            <div class="mt-4">
              <x-label for="dopust" :value="__('Plačeni dopust')" />
              <input onclick="ShowHideDiv(this)" id="dopust" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="checkbox" name="dopust" {{$day->dopust ? 'checked' : ''}} />
            </div>
```
### app\Http\Controllers\DayController.php
```
  public function create(Request $request)
  {
    $day = new Day;
    if (null != $request->input('date')) {
      $day->date = $request->input('date');
      if ($request->input('sick') == true) $day->sick = true;
      if ($request->input('go') == true) $day->go = true;
      if ($request->input('dopust') == true) $day->dopust = true;
      if ($request->input('start') != null) $day->start = $request->input('start');
      if ($request->input('duration') != null) $day->duration = $request->input('duration');
    }
    return view('days.create')->with(compact('day'));
  }

  public function store(Request $request)
  {
    $this->validate($request, [
      'date' => 'required'
    ]);
    $old_day = Day::where('user_id', '=', Auth::user()->id)->where('date', '=', date('Y-m-d', strtotime($request->input('date'))))->get();
    $day = new Day;
    $day->date = $request->input('date');
    $day->user_id = Auth::user()->id;
    if (null != $request->input('sick')) $day->sick = $request->input('sick') == 'on' ? true : false;
    if (null != $request->input('go')) $day->go = $request->input('go') == 'on' ? true : false;
    if (null != $request->input('dopust')) $day->dopust = $request->input('dopust') == 'on' ? true : false;
    if (null != $request->input('night_duration')) $day->night_duration = $request->input('night_duration') ? $request->input('night_duration') : $day->night_duration;
    $day->start = $request->input('start');
    $day->duration = $request->input('duration');
    if (count($old_day) > 0) return view('days.edit')->with(compact('old_day', 'day'));
    $day->save();
    return redirect(route('month').'/'.$day->date->format('m.Y'))->with('success', 'Day Updated');
  }

  public function update(Request $request, $date)
  {
    $day = Day::where('user_id', '=', Auth::user()->id)->where('date', '=', date('Y-m-d', strtotime($date)))->get();
    if (null != $request->input('sick')) $day[0]->sick = $request->input('sick') == 'on' ? true : false;
    if (null != $request->input('go')) $day[0]->go = $request->input('go') == 'on' ? true : false;
    if (null != $request->input('dopust')) $day[0]->dopust = $request->input('dopust') == 'dopust' ? true : false;
    $day[0]->night_duration = $request->input('night_duration') ? $request->input('night_duration') : $day[0]->night_duration;
    $day[0]->start = $request->input('start');
    $day[0]->duration = $request->input('duration');
    $day[0]->save();
    return redirect(route('days.show', ['day' => $day[0]->date->format('d.m.Y')]))->with('success', 'Day Updated');
  }
```
### app\Http\Controllers\PlatnaLista.php
```
    $hoursNormGO = 0;
    $daysGO = 0;

      if ($daysColection->where('date', '=', $from->addDays($i))->where('go', '=', true)->first() != null) {
        $hoursNormGO += $def_h;
        if ($def_h > 0 ) $daysGO++;
      }

    // 1.1. Za redoviti rad
    $hoursWorkNorm = $hoursNorm - $hoursNormHoli - $hoursNormSick - $hoursNormGO;
    $h1_1 = $minWork / 60 > $hoursWorkNorm ? $hoursWorkNorm : $minWork / 60;
    $data['1.1.h'] = number_format($h1_1, 2, ',', '.'); //'158,00';
    $data['1.1.kn'] = number_format($h1_1 * $perHour, 2, ',', '.'); //'4.867,98';
    // 1.4 Za prekovremeni rad
    $h1_4 = $prekovremeni;
    $overWork = $minWork / 60 - $hoursWorkNorm;

    $data['1.4.h'] = number_format($h1_4, 2, ',', '.') . ' (' . $overWork . ')'; //'24,00';
    $data['1.4.kn'] = number_format($h1_4 * $perHour * 1.5, 2, ',', '.'); //'1.109,16';
    // 1.x Za godišnji
    $h1_go = $hoursNormGO;

    $data['1.go.h'] = number_format($h1_go, 2, ',', '.') . ' (' . $daysGO . ')';
    $data['1.go.kn'] = number_format($h1_go * $perHour, 2, ',', '.');

    // 4. ZBROJENI IZNOSI PRIMITAKA PO SVIM OSNOVAMA PO STAVKAMA 1. DO 3.
    $kn5 = ($h1_1 + $h1_4 * 1.5 + $hoursNormHoli + $hoursNormSick * 0.7588 + $hoursNormGO + $minWorkSunday / 60 * 0.35 + $minWorkHoli / 60 * 0.5) * $perHour;
```
### resources\views\platna-lista.blade.php
```
              @if($data['1.go.h'] > 0)
              <tr>
                <td class="w-3/4 border p-2 pl-6" colspan="2">GO (pretpostavljam da se ovak računa)</td>
                <td class="w-1/8 border p-2 text-center">{{ $data['1.go.h'] }}</td>
                <td class="w-1/8 border p-2 text-right">{{ $data['1.go.kn'] }}</td>
              </tr>
              @endif
```
```
git add .
git commit -am "add GO [laravel]"
php artisan make:migration add_zaposlen_to_users_table --table=users
```

