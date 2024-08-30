<!DOCTYPE html>
<html>
<head>
    <title>Chat Room</title>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    @vite('resources/js/chat.js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* Basic chat container styling */
        #chat {
            display: flex;
            height: 100vh;
        }
        .user-list {
            width: 200px;
            background-color: #f1f1f1;
            border-right: 1px solid #ddd;
            padding: 10px;
            overflow-y: auto;
        }
        .user-item {
            cursor: pointer;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 5px;
            background-color: #e1ffc7;
        }
        .user-item:hover {
            background-color: #d1f5a0;
        }
        .message-list {
            flex-grow: 1;
            padding: 10px;
            border-left: 1px solid #ddd;
            background-color: #fff;
            overflow-y: auto;
        }
        .message {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
            background-color: #e1ffc7;
        }
        .input-area {
            display: flex;
            flex-direction: column;
            margin-top: 10px;
        }
        .input-area textarea {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            width: 100%;
        }
        .input-area button {
            align-self: flex-end;
            padding: 10px;
            border-radius: 5px;
            border: none;
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }
        .input-area button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div id="chat">
        <!-- User list -->
        <div class="user-list">
            <div class="user-item" @click="selectUser(user)" v-for="user in users" :key="user.id">
                {{ user.name }}
            </div>
        </div>

        <!-- Chat messages display -->
        <div class="message-list">
            <div class="message" v-for="message in messages" :key="message.id">
                <strong>{{ message.sender.name }}:</strong> {{ message.message }}
            </div>
        </div>

        <!-- Input area for sending messages -->
        <div class="input-area" v-if="selectedUser">
            <textarea v-model="newMessage" placeholder="Type a message..."></textarea>
            <button @click="sendMessage">Send</button>
        </div>
    </div>
</body>

</html>
