<!DOCTYPE html>
<html>
<head>
    <title>Chat Room</title>
    @vite('resources/js/chat.js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        #chat {
            width: 400px;
            margin: 0 auto;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
            border-radius: 5px;
            height: 500px;
            overflow-y: auto;
        }
        .message {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
            background-color: #e1ffc7;
            max-width: 80%;
            word-wrap: break-word;
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
        <div v-for="message in messages" :key="message.id" class="message">
            <strong>@{{ message.user_name }}:</strong> @{{ message.message }}
        </div>
        <div class="input-area">
            <input v-model="userName" placeholder="Your name">
            <textarea v-model="newMessage" placeholder="Type a message..."></textarea>
            <button @click="sendMessage">Send</button>
        </div>
    </div>
</body>
</html>
