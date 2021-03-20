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
