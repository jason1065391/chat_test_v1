import { createApp } from 'vue';
import Pusher from 'pusher-js';

const app = createApp({
  data() {
    return {
      users: [],            // 所有用户
      selectedUser: null,  // 选中的聊天对象
      messages: [],        // 消息列表
      newMessage: '',       // 新消息
      currentUserId: null // 初始化为 null
    };
  },
  created() {
    this.currentUserId = this.getCurrentUserId(); // 在 created 钩子中初始化 currentUserId
    console.log('Current User ID:', this.currentUserId); // 输出 currentUserId 以便调试
    this.fetchUsers();
    this.setupPusher();
  },
  methods: {
    getCurrentUserId() {
      const meta = document.querySelector('meta[name="current-user-id"]');
      return meta ? parseInt(meta.getAttribute('content'), 10) : null;
    },
    
    async fetchMessages() {
      if (!this.selectedUser) return;

      try {
        const response = await fetch(`/chat_test_v1/public/messages?user_id=${this.selectedUser.id}`);
        const data = await response.json();
        this.messages = data.map(message => ({
          ...message,
          timestamp: new Date(message.created_at).toLocaleTimeString(),
          sender: message.sender
        }));
      } catch (error) {
        console.error('Error fetching messages:', error);
      }
    },
  
    async sendMessage() {
      if (this.selectedUser && this.newMessage && this.currentUserId) {
        try {
          const response = await fetch('/chat_test_v1/public/send-message', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
              sender_id: this.currentUserId,
              receiver_id: this.selectedUser ? this.selectedUser.id : null,
              message: this.newMessage
            })
          });

          const data = await response.json();
          if (data.success) {
            this.newMessage = '';
            // Optionally add the message to the list here
          }
        } catch (error) {
          console.error('Error sending message:', error);
        }
      } else {
        console.error('Cannot send message: invalid user or message');
      }
    },
    
    setupPusher() {
      const pusher = new Pusher('d7aa367e75ad2076f618', {
        cluster: 'ap3',
        forceTLS: true
      });

      const channel = pusher.subscribe('chat');
      channel.bind('message-sent', data => {
        if (data.message && (data.message.receiver_id === this.selectedUser?.id || data.message.sender_id === this.selectedUser?.id)) {
          this.addMessage(data.message);
        } else {
          console.warn('Message not relevant:', data);
        }
      });

      // Debugging logs
      pusher.connection.bind('connected', function() {
        console.log('Connected to Pusher');
      });

      pusher.connection.bind('error', function(err) {
        console.error('Pusher connection error:', err);
      });
    },
    
    addMessage(message) {
      if (message) {
        this.messages.push({
          ...message,
          timestamp: new Date(message.created_at).toLocaleTimeString()
        });
        this.$nextTick(() => {
          const chat = document.querySelector('.message-list');
          if (chat) {
            chat.scrollTop = chat.scrollHeight;
          }
        });
      }
    },

    selectUser(user) {
      this.selectedUser = user;
      this.fetchMessages();
    },
    
    fetchUsers() {
      // Fetch users from API or some source
    }
  }
});

// 挂载 Vue 应用
app.mount('#chat');
