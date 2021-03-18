# Laravel Vue
### resources\views\dashboard.blade.php
```php
          <div id="app">
            <example-component></example-component>
          </div>
```
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
        <div class="p-6 bg-white border-b border-gray-200">
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
