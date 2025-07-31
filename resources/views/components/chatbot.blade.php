<!-- Chatbot Widget -->
<div id="chatbot-widget" class="fixed bottom-4 right-4 z-50 w-auto">
    <!-- Chatbot Toggle Button -->
    <button id="chatbot-toggle" class="bg-pink-600 hover:bg-pink-700 text-white rounded-full w-12 h-12 sm:w-16 sm:h-16 flex items-center justify-center shadow-lg transition-all duration-300 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-pink-400">
        <svg id="chat-icon" class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
        </svg>
        <svg id="close-icon" class="w-6 h-6 sm:w-8 sm:h-8 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>

    <!-- Chatbot Window -->
    <div id="chatbot-window" class="hidden fixed bottom-16 sm:bottom-20 right-0 w-80 sm:w-96 lg:w-[500px] h-[400px] sm:h-[500px] lg:h-[650px] bg-white rounded-lg shadow-2xl border border-gray-200 flex flex-col max-w-full" style="max-width:100vw;">
        <!-- Header -->
        <div class="bg-gradient-to-r from-pink-600 to-purple-600 text-white p-3 sm:p-4 rounded-t-lg">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-sm sm:text-base">Hanaya Assistant</h3>
                    <p class="text-xs sm:text-sm text-pink-100">Online now</p>
                </div>
                <div class="ml-auto">
                    <div class="w-2 h-2 sm:w-3 sm:h-3 bg-green-400 rounded-full animate-pulse"></div>
                </div>
            </div>
        </div>

        <!-- Messages Container -->
        <div id="chat-messages" class="flex-1 p-2 sm:p-4 overflow-y-auto space-y-3 text-xs sm:text-sm">
            <div class="flex items-start space-x-2">
                <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gradient-to-r from-pink-500 to-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="bg-gray-100 rounded-lg px-3 py-2 max-w-xs sm:max-w-sm">
                    <p>Xin chào! Tôi là trợ lý ảo của Hanaya Shop. Tôi có thể giúp bạn:</p>
                    <ul class="mt-2 space-y-1 text-xs">
                        <li>🔍 Tìm kiếm sản phẩm</li>
                        <li>📦 Kiểm tra đơn hàng</li>
                        <li>🏪 Thông tin cửa hàng</li>
                        <li>📰 Tin tức mới nhất</li>
                    </ul>
                    <p class="mt-2">Bạn cần hỗ trợ gì hôm nay? 🌸</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="px-2 sm:px-4 py-2 bg-gray-50 border-t border-gray-100">
            <div class="flex flex-wrap gap-1">
                <button class="quick-action text-xs px-2 py-1 bg-pink-100 text-pink-700 rounded-full hover:bg-pink-200 transition-colors" data-message="Tìm hoa xà phòng">
                    🧼 Hoa xà phòng
                </button>
                <button class="quick-action text-xs px-2 py-1 bg-purple-100 text-purple-700 rounded-full hover:bg-purple-200 transition-colors" data-message="Xem đơn hàng của tôi">
                    📦 Đơn hàng
                </button>
                <button class="quick-action text-xs px-2 py-1 bg-green-100 text-green-700 rounded-full hover:bg-green-200 transition-colors" data-message="Thông tin cửa hàng">
                    🏪 Cửa hàng
                </button>
                <button class="quick-action text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors" data-message="Tin tức mới nhất">
                    📰 Tin tức
                </button>
            </div>
        </div>

        <!-- Input Area -->
        <div class="p-2 sm:p-4 border-t border-gray-200">
            <div class="flex space-x-2">
                <input type="text" id="chat-input" placeholder="Nhập tin nhắn..." 
                       class="flex-1 px-2 py-2 text-xs sm:text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent" style="min-width:0;">
                <button id="send-message" class="bg-gradient-to-r from-pink-500 to-purple-600 text-white p-2 rounded-lg hover:from-pink-600 hover:to-purple-700 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-pink-400">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Typing Indicator -->
        <div id="typing-indicator" class="hidden px-3 sm:px-4 py-2">
            <div class="flex items-center space-x-2">
                <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gradient-to-r from-pink-500 to-purple-600 rounded-full flex items-center justify-center">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="bg-gray-100 rounded-lg px-3 py-2">
                    <div class="flex space-x-1">
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                    </div>
                </div>
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
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
            console.error('Error:', error);
            addMessage('Xin lỗi, có lỗi xảy ra. Vui lòng thử lại sau.', 'bot');
        });
    }

    // Event listeners
    sendBtn.addEventListener('click', sendMessage);
    chatInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            sendMessage();
        }
    });

    // Quick actions
    quickActions.forEach(btn => {
        btn.addEventListener('click', function() {
            const message = this.getAttribute('data-message');
            chatInput.value = message;
            sendMessage();
        });
    });

    // Add message to chat
    function addMessage(text, sender) {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'flex items-start space-x-2';
        
        if (sender === 'user') {
            messageDiv.classList.add('justify-end');
            messageDiv.innerHTML = `
                <div class="bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-lg px-3 py-2 max-w-xs sm:max-w-sm text-right">
                    <p>${escapeHtml(text)}</p>
                </div>
                <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gray-300 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            `;
        } else {
            messageDiv.innerHTML = `
                <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gradient-to-r from-pink-500 to-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="bg-gray-100 rounded-lg px-3 py-2 max-w-xs sm:max-w-sm">
                    <div class="chatbot-response">${formatResponse(text)}</div>
                </div>
            `;
        }

        messagesContainer.appendChild(messageDiv);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    function formatResponse(text) {
        // Convert URLs to clickable links
        text = text.replace(/(https?:\/\/[^\s]+)/g, '<a href="$1" target="_blank" class="text-pink-600 hover:text-pink-800 underline">$1</a>');
        
        // Convert route links to internal navigation
        text = text.replace(/🔗 (\/[^\s\n]+)/g, '<a href="$1" class="inline-block mt-1 px-2 py-1 bg-pink-100 text-pink-700 rounded text-xs hover:bg-pink-200 transition-colors">Xem ngay →</a>');
        
        // Format line breaks
        text = text.replace(/\n/g, '<br>');
        
        // Format bold text
        text = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        
        return text;
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

    // Handle responsive positioning on mobile
    function adjustChatbotPosition() {
        const chatWindow = document.getElementById('chatbot-window');
        const widget = document.getElementById('chatbot-widget');
        const viewportWidth = window.innerWidth;
        if (viewportWidth < 640) { // Mobile breakpoint
            chatWindow.style.position = 'fixed';
            chatWindow.style.left = '50%';
            chatWindow.style.transform = 'translateX(-50%)';
            chatWindow.style.right = 'auto';
            chatWindow.style.width = 'calc(100vw - 1.5rem)';
            chatWindow.style.maxWidth = 'calc(100vw - 1.5rem)';
            chatWindow.style.height = '60vh';
            chatWindow.style.minHeight = '320px';
            chatWindow.style.bottom = '5.5rem';
            widget.style.right = '1rem';
            widget.style.left = 'auto';
        } else {
            chatWindow.style.position = 'fixed';
            chatWindow.style.right = '0';
            chatWindow.style.left = 'auto';
            chatWindow.style.transform = '';
            chatWindow.style.width = '';
            chatWindow.style.maxWidth = '';
            chatWindow.style.height = '';
            chatWindow.style.minHeight = '';
            chatWindow.style.bottom = '';
            widget.style.right = '';
            widget.style.left = '';
        }
    }
    window.addEventListener('resize', adjustChatbotPosition);
    adjustChatbotPosition();
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

.chatbot-response a {
    word-break: break-word;
}

/* Mobile adjustments */
@media (max-width: 640px) {
    #chatbot-widget {
        bottom: 1.5rem !important;
        right: 1rem !important;
        left: auto !important;
        width: auto !important;
        z-index: 50;
    }
    #chatbot-window {
        left: 50% !important;
        transform: translateX(-50%) !important;
        right: auto !important;
        width: calc(100vw - 1.5rem) !important;
        max-width: calc(100vw - 1.5rem) !important;
        min-height: 320px !important;
        height: 60vh !important;
        bottom: 5.5rem !important;
        z-index: 51;
    }
    #chat-messages {
        padding: 0.5rem !important;
    }
    .quick-action {
        font-size: 13px !important;
        padding: 0.25rem 0.75rem !important;
    }
    #chat-input {
        font-size: 13px !important;
        padding: 0.5rem 0.75rem !important;
    }
}
</style>
