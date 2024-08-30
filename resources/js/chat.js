import { createApp } from 'vue';
import Pusher from 'pusher-js';

// Vue 應用組件
const app = createApp({
  data() {
    return {
      userName: '',
      newMessage: '',
      messages: []
    };
  },
  created() {
    this.fetchMessages();

    // 初始化 Pusher
    const pusher = new Pusher('d7aa367e75ad2076f618', {
      cluster: 'ap3',
      forceTLS: true
    });

    // 訂閱聊天頻道
    const channel = pusher.subscribe('chat');
    channel.bind('message-sent', (data) => {
      this.addMessage(data.message);
    });
  },
  methods: {
    sendMessage() {
      if (this.userName && this.newMessage) {
        fetch('/chat_test_v1/public/send-message', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({
            user_name: this.userName,
            message: this.newMessage
          })
        }).then(response => response.json())
          .then(data => {
            this.newMessage = '';  // 清空輸入框
          })
          .catch(error => {
            console.error('Error sending message:', error);
          });
      }
    },
    fetchMessages() {
      fetch('/chat_test_v1/public/messages')
        .then(response => response.json())
        .then(data => {
          this.messages = data.map(message => ({
            ...message,
            timestamp: new Date(message.created_at).toLocaleTimeString()
          }));
        })
        .catch(error => {
          console.error('Error fetching messages:', error);
        });
    },
    addMessage(message) {
      this.messages.push({
        ...message,
        timestamp: new Date(message.created_at).toLocaleTimeString()
      });
      this.$nextTick(() => {
        // 滾動到最底部顯示最新訊息
        const chat = document.querySelector('#chat');
        chat.scrollTop = chat.scrollHeight;
      });
    }
  }
});

// 挂载 Vue 应用
app.mount('#chat');
