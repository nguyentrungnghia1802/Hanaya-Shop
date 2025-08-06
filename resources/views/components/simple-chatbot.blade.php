<!-- Simple Chatbot Widget -->
<div id="simple-chatbot-widget" class="fixed bottom-4 right-4 z-50">
    <!-- Chatbot Toggle Button -->
    <button id="simple-chatbot-toggle" class="bg-pink-600 hover:bg-pink-700 text-white rounded-full w-14 h-14 flex items-center justify-center shadow-lg transition-all duration-300 hover:scale-110">
        <svg id="simple-chat-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
        </svg>
        <svg id="simple-close-icon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>

    <!-- Chatbot Window -->
    <div id="simple-chatbot-window" class="hidden fixed bottom-20 right-4 w-80 h-96 bg-white rounded-lg shadow-2xl border border-gray-200 flex flex-col">
        <!-- Header -->
        <div class="bg-gradient-to-r from-pink-600 to-purple-600 text-white p-3 rounded-t-lg">
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-sm">Hanaya Assistant</h3>
                    <p class="text-xs text-pink-100">Trực tuyến</p>
                </div>
                <div class="ml-auto">
                    <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                </div>
            </div>
        </div>

        <!-- Messages Container -->
        <div id="simple-chat-messages" class="flex-1 p-3 overflow-y-auto space-y-2 text-sm">
            <div class="flex items-start space-x-2">
                <div class="w-6 h-6 bg-gradient-to-r from-pink-500 to-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="bg-gray-100 rounded-lg px-3 py-2 max-w-xs">
                    <p class="text-xs">Xin chào! Tôi là trợ lý ảo của Hanaya Shop. Tôi có thể giúp bạn tìm sản phẩm, thông tin cửa hàng, giá cả và nhiều thứ khác. Bạn cần tôi giúp gì?</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="px-3 py-2 bg-gray-50 border-t border-gray-100">
            <div class="flex flex-wrap gap-1">
                <button class="simple-quick-action text-xs px-2 py-1 bg-pink-100 text-pink-700 rounded-full hover:bg-pink-200" data-message="sản phẩm">
                    🌸 Sản phẩm
                </button>
                <button class="simple-quick-action text-xs px-2 py-1 bg-purple-100 text-purple-700 rounded-full hover:bg-purple-200" data-message="giá cả">
                    💰 Giá cả
                </button>
                <button class="simple-quick-action text-xs px-2 py-1 bg-green-100 text-green-700 rounded-full hover:bg-green-200" data-message="cửa hàng">
                    🏪 Cửa hàng
                </button>
                <button class="simple-quick-action text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200" data-message="giao hàng">
                    🚚 Giao hàng
                </button>
            </div>
        </div>

        <!-- Input Area -->
        <div class="p-3 border-t border-gray-200">
            <div class="flex space-x-2">
                <input type="text" id="simple-chat-input" placeholder="Nhập tin nhắn..." 
                       class="flex-1 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                <button id="simple-send-message" class="bg-gradient-to-r from-pink-500 to-purple-600 text-white p-2 rounded-lg hover:from-pink-600 hover:to-purple-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('simple-chatbot-toggle');
    const chatWindow = document.getElementById('simple-chatbot-window');
    const chatInput = document.getElementById('simple-chat-input');
    const sendBtn = document.getElementById('simple-send-message');
    const messagesContainer = document.getElementById('simple-chat-messages');
    const chatIcon = document.getElementById('simple-chat-icon');
    const closeIcon = document.getElementById('simple-close-icon');
    const quickActions = document.querySelectorAll('.simple-quick-action');

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

        // Add user message
        addMessage(message, 'user');
        chatInput.value = '';

        // Send to server
        fetch('/simple-chatbot', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ message: message })
        })
        .then(response => response.json())
        .then(data => {
            if (data.response) {
                addMessage(data.response, 'bot');
            }
        })
        .catch(error => {
            addMessage('Xin lỗi, có lỗi xảy ra. Vui lòng thử lại.', 'bot');
        });
    }

    // Add message to chat
    function addMessage(text, sender) {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'flex items-start space-x-2';

        if (sender === 'user') {
            messageDiv.classList.add('justify-end');
            messageDiv.innerHTML = `
                <div class="bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-lg px-3 py-2 max-w-xs text-right">
                    <p class="text-xs">${escapeHtml(text)}</p>
                </div>
                <div class="w-6 h-6 bg-gray-300 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-3 h-3 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            `;
        } else {
            messageDiv.innerHTML = `
                <div class="w-6 h-6 bg-gradient-to-r from-pink-500 to-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="bg-gray-100 rounded-lg px-3 py-2 max-w-xs">
                    <div class="text-xs">${formatResponse(text)}</div>
                </div>
            `;
        }

        messagesContainer.appendChild(messageDiv);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    function formatResponse(text) {
        // Convert URLs to clickable links
        text = text.replace(/(https?:\/\/[^\s]+)/g, '<a href="$1" target="_blank" class="text-pink-600 underline">$1</a>');
        // Format line breaks
        text = text.replace(/\n/g, '<br>');
        return text;
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
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
            const message = this.getAttribute('data-message');
            chatInput.value = message;
            sendMessage();
        });
    });
});
</script>

<style>
#simple-chat-messages {
    scrollbar-width: thin;
    scrollbar-color: #e5e7eb #f9fafb;
}

#simple-chat-messages::-webkit-scrollbar {
    width: 4px;
}

#simple-chat-messages::-webkit-scrollbar-track {
    background: #f9fafb;
}

#simple-chat-messages::-webkit-scrollbar-thumb {
    background: #e5e7eb;
    border-radius: 2px;
}
</style>
