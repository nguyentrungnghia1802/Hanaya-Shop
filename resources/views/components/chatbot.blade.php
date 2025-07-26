<!-- Chatbot Widget -->
<div id="chatbot-widget" class="fixed bottom-4 right-4 z-50">
    <!-- Chatbot Toggle Button -->
    <button id="chatbot-toggle" class="bg-pink-600 hover:bg-pink-700 text-white rounded-full w-16 h-16 flex items-center justify-center shadow-lg transition-all duration-300 hover:scale-110">
        <svg id="chat-icon" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
        </svg>
        <svg id="close-icon" class="w-8 h-8 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>

    <!-- Chatbot Window -->
    <div id="chatbot-window" class="hidden absolute bottom-20 right-0 w-[500px] h-[650px] bg-white rounded-lg shadow-2xl border border-gray-200 flex flex-col">
        <!-- Header -->
        <div class="bg-gradient-to-r from-pink-600 to-purple-600 text-white p-4 rounded-t-lg">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold">Chatbot Hanaya</h3>
                    <p class="text-xs text-pink-100">Trợ lý ảo của bạn</p>
                </div>
            </div>
        </div>

        <!-- Messages Container -->
        <div id="chat-messages" class="flex-1 p-4 overflow-y-auto space-y-3">
            <div class="flex items-start space-x-2">
                <div class="w-6 h-6 bg-pink-500 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <div class="bg-gray-100 rounded-lg p-3 max-w-xs">
                    <p class="text-sm text-gray-800">Xin chào! Tôi có thể giúp gì cho bạn hôm nay?</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="px-4 py-2 bg-gray-50">
            <div class="flex flex-wrap gap-1">
                <button class="quick-action text-xs bg-white border border-gray-200 rounded-full px-3 py-1 hover:bg-gray-100 transition" data-message="danh mục sản phẩm">
                    📂 Danh mục
                </button>
                <button class="quick-action text-xs bg-white border border-gray-200 rounded-full px-3 py-1 hover:bg-gray-100 transition" data-message="sản phẩm mới">
                    🆕 Sản phẩm mới
                </button>
                <button class="quick-action text-xs bg-white border border-gray-200 rounded-full px-3 py-1 hover:bg-gray-100 transition" data-message="thông tin cửa hàng">
                    🏪 Liên hệ
                </button>
            </div>
        </div>

        <!-- Input Area -->
        <div class="p-4 border-t border-gray-200">
            <div class="flex space-x-2">
                <input type="text" id="chat-input" placeholder="Nhập tin nhắn..." 
                       class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                <button id="send-message" class="bg-pink-600 hover:bg-pink-700 text-white rounded-lg px-4 py-2 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Typing Indicator -->
        <div id="typing-indicator" class="hidden px-4 py-2">
            <div class="flex items-center space-x-2">
                <div class="w-6 h-6 bg-pink-500 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <div class="typing-dots flex space-x-1">
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                </div>
                <span class="text-sm text-gray-500">Đang nhập...</span>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('chatbot-toggle');
    const chatWindow = document.getElementById('chatbot-window');
    const chatInput = document.getElementById('chat-input');
    const sendBtn = document.getElementById('send-message');
    const messagesContainer = document.getElementById('chat-messages');
    const typingIndicator = document.getElementById('typing-indicator');
    const chatIcon = document.getElementById('chat-icon');
    const closeIcon = document.getElementById('close-icon');
    const quickActions = document.querySelectorAll('.quick-action');

    let isOpen = false;

    // Toggle chatbot window
    toggleBtn.addEventListener('click', function() {
        isOpen = !isOpen;
        if (isOpen) {
            chatWindow.classList.remove('hidden');
            chatIcon.classList.add('hidden');
            closeIcon.classList.remove('hidden');
            chatInput.focus();
        } else {
            chatWindow.classList.add('hidden');
            chatIcon.classList.remove('hidden');
            closeIcon.classList.add('hidden');
        }
    });

    // Send message
    function sendMessage() {
        const message = chatInput.value.trim();
        if (!message) return;

        // Add user message to chat
        addMessage(message, 'user');
        chatInput.value = '';

        // Show typing indicator
        showTypingIndicator();

        // Send to server
        fetch('/chatbot', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ message: message })
        })
        .then(response => response.json())
        .then(data => {
            hideTypingIndicator();
            addMessage(data.response, 'bot');
        })
        .catch(error => {
            hideTypingIndicator();
            addMessage('Xin lỗi, có lỗi xảy ra. Vui lòng thử lại sau.', 'bot');
            console.error('Error:', error);
        });
    }

    // Event listeners
    sendBtn.addEventListener('click', sendMessage);
    chatInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });

    // Quick actions
    quickActions.forEach(btn => {
        btn.addEventListener('click', function() {
            const message = this.dataset.message;
            chatInput.value = message;
            sendMessage();
        });
    });

    // Add message to chat
    function addMessage(text, sender) {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'flex items-start space-x-2';
        
        if (sender === 'user') {
            messageDiv.className += ' justify-end';
            messageDiv.innerHTML = `
                <div class="bg-pink-600 text-white rounded-lg p-3 max-w-xs">
                    <p class="text-sm">${escapeHtml(text)}</p>
                </div>
                <div class="w-6 h-6 bg-gray-400 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
            `;
        } else {
            messageDiv.innerHTML = `
                <div class="w-6 h-6 bg-pink-500 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <div class="bg-gray-100 rounded-lg p-3 max-w-xs">
                    <p class="text-sm text-gray-800 whitespace-pre-line">${escapeHtml(text)}</p>
                </div>
            `;
        }

        messagesContainer.appendChild(messageDiv);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    function showTypingIndicator() {
        typingIndicator.classList.remove('hidden');
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    function hideTypingIndicator() {
        typingIndicator.classList.add('hidden');
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
});
</script>

<style>
@keyframes bounce {
    0%, 80%, 100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-10px);
    }
}

.animate-bounce {
    animation: bounce 1s infinite;
}

#chat-messages {
    scrollbar-width: thin;
    scrollbar-color: #e5e7eb #f9fafb;
}

#chat-messages::-webkit-scrollbar {
    width: 6px;
}

#chat-messages::-webkit-scrollbar-track {
    background: #f9fafb;
    border-radius: 3px;
}

#chat-messages::-webkit-scrollbar-thumb {
    background: #e5e7eb;
    border-radius: 3px;
}

#chat-messages::-webkit-scrollbar-thumb:hover {
    background: #d1d5db;
}
</style>