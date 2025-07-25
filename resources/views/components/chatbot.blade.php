<!-- Chatbot Widget -->
<div id="chatbot-widget" class="fixed bottom-4 right-4 z-50">
    <!-- Chatbot Toggle Button -->
    <button id="chatbot-toggle" 
            class="bg-pink-600 hover:bg-pink-700 text-white rounded-full w-12 h-12 md:w-14 md:h-14 lg:w-16 lg:h-16 flex items-center justify-center shadow-lg transition-all duration-300 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2"
            aria-label="Open chat assistant"
            aria-expanded="false">
        <svg id="chat-icon" class="w-5 h-5 md:w-6 md:h-6 lg:w-8 lg:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
        </svg>
        <svg id="close-icon" class="w-5 h-5 md:w-6 md:h-6 lg:w-8 lg:h-8 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
        <!-- Notification Badge -->
        <span id="notification-badge" class="absolute -top-1 -right-1 w-3 h-3 md:w-4 md:h-4 text-xs font-bold leading-none text-white bg-red-500 rounded-full animate-pulse hidden" aria-label="New message"></span>
    </button>

    <!-- Chatbot Window -->
    <div id="chatbot-window" 
         class="chatbot-window fixed inset-0 md:absolute md:inset-auto md:bottom-16 md:right-0 w-full h-full md:w-[380px] lg:w-[420px] xl:w-[480px] md:h-[550px] lg:h-[600px] xl:h-[650px] bg-white md:rounded-xl shadow-2xl border-0 md:border border-gray-200 overflow-hidden"
         role="dialog" 
         aria-modal="true" 
         aria-labelledby="chatbot-header"
         aria-describedby="chatbot-description">
        <!-- Header -->
        <div id="chatbot-header" class="bg-gradient-to-r from-pink-600 to-purple-600 text-white p-3 md:p-4 md:rounded-t-xl relative flex-shrink-0">
            <!-- Mobile Close Button -->
            <button id="mobile-close" 
                    class="absolute top-3 right-3 md:hidden w-8 h-8 flex items-center justify-center rounded-full bg-white bg-opacity-20 hover:bg-opacity-30 transition-colors focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50"
                    aria-label="Close chat">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            
            <div class="flex items-center space-x-3 pr-10 md:pr-0">
                <div class="w-8 h-8 md:w-10 md:h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <h3 class="font-semibold text-sm md:text-base truncate">{{ config('constants.shop_name') }} Assistant</h3>
                    <p id="chatbot-description" class="text-xs text-pink-100 truncate">Your shopping helper • Online</p>
                </div>
            </div>
        </div>

        <!-- Messages Container -->
        <div id="chat-messages" 
             class="flex-1 p-3 md:p-4 overflow-y-auto space-y-3 bg-gray-50 min-h-0"
             role="log"
             aria-live="polite"
             aria-label="Chat messages">
            <!-- Welcome Message -->
            <div class="flex items-start space-x-2 animate-fadeIn">
                <div class="w-6 h-6 md:w-8 md:h-8 bg-pink-500 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-3 h-3 md:w-4 md:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <div class="bg-white rounded-lg p-3 shadow-sm max-w-[85%] md:max-w-xs border border-gray-100">
                    <p class="text-sm text-gray-800">Hello! How can I help you today? Try asking about our products, orders, or store info! 😊</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="px-3 md:px-4 py-2 bg-gray-50 border-t border-gray-200 flex-shrink-0">
            <div class="flex flex-wrap gap-1 md:gap-2">
                <button class="quick-action text-xs px-2 md:px-3 py-1 bg-white border border-gray-200 rounded-full hover:bg-pink-50 hover:border-pink-200 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-opacity-50" data-message="best sellers">
                    🏆 Best Sellers
                </button>
                <button class="quick-action text-xs px-2 md:px-3 py-1 bg-white border border-gray-200 rounded-full hover:bg-pink-50 hover:border-pink-200 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-opacity-50" data-message="sale products">
                    🔥 Sales
                </button>
                <button class="quick-action text-xs px-2 md:px-3 py-1 bg-white border border-gray-200 rounded-full hover:bg-pink-50 hover:border-pink-200 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-opacity-50" data-message="categories">
                    📂 Categories
                </button>
                <button class="quick-action text-xs px-2 md:px-3 py-1 bg-white border border-gray-200 rounded-full hover:bg-pink-50 hover:border-pink-200 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-opacity-50" data-message="store info">
                    🏪 Contact
                </button>
                <button class="quick-action text-xs px-2 md:px-3 py-1 bg-white border border-gray-200 rounded-full hover:bg-pink-50 hover:border-pink-200 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-opacity-50" data-message="help">
                    ❓ Help
                </button>
            </div>
        </div>

        <!-- Input Area -->
        <div class="p-3 md:p-4 border-t border-gray-200 bg-white flex-shrink-0">
            <div class="flex space-x-2">
                <label for="chat-input" class="sr-only">Type your message</label>
                <input type="text" 
                       id="chat-input" 
                       placeholder="Type your message..." 
                       class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all duration-200 disabled:bg-gray-100 disabled:cursor-not-allowed"
                       maxlength="500" 
                       autocomplete="off"
                       aria-describedby="char-counter">
                <button id="send-message" 
                        class="bg-pink-600 hover:bg-pink-700 disabled:bg-gray-400 text-white rounded-lg px-3 md:px-4 py-2 transition-colors duration-200 flex-shrink-0 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 disabled:cursor-not-allowed" 
                        disabled
                        aria-label="Send message">
                    <svg id="send-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                    <svg id="loading-icon" class="w-4 h-4 animate-spin hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" stroke-dasharray="31.416" stroke-dashoffset="31.416"></circle>
                        <path d="M4 12a8 8 0 018-8v8z" fill="currentColor"></path>
                    </svg>
                </button>
            </div>
            <div class="mt-1 text-xs text-gray-500" id="char-counter" aria-live="polite">0/500</div>
        </div>

        <!-- Typing Indicator -->
        <div id="typing-indicator" class="hidden px-3 md:px-4 py-2 bg-gray-50 border-t border-gray-200">
            <div class="flex items-center space-x-2 animate-fadeIn">
                <div class="w-6 h-6 md:w-8 md:h-8 bg-pink-500 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-3 h-3 md:w-4 md:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <div class="bg-white rounded-lg p-3 shadow-sm border border-gray-100">
                    <div class="flex space-x-1">
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                    </div>
                </div>
                <span class="text-xs text-gray-500">AI is typing...</span>
            </div>
        </div>
    </div>

    <!-- Mobile Backdrop -->
    <div id="mobile-backdrop" class="hidden fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden"></div>
</div>

<!-- Chatbot JavaScript - CSP Compliant -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const toggleBtn = document.getElementById('chatbot-toggle');
    const chatWindow = document.getElementById('chatbot-window');
    const mobileBackdrop = document.getElementById('mobile-backdrop');
    const mobileClose = document.getElementById('mobile-close');
    const chatInput = document.getElementById('chat-input');
    const sendBtn = document.getElementById('send-message');
    const messagesContainer = document.getElementById('chat-messages');
    const typingIndicator = document.getElementById('typing-indicator');
    const chatIcon = document.getElementById('chat-icon');
    const closeIcon = document.getElementById('close-icon');
    const sendIcon = document.getElementById('send-icon');
    const loadingIcon = document.getElementById('loading-icon');
    const charCounter = document.getElementById('char-counter');
    const quickActions = document.querySelectorAll('.quick-action');
    const notificationBadge = document.getElementById('notification-badge');

    // State
    let isOpen = false;
    let isMobile = window.innerWidth < 768;
    let isLoading = false;
    let messageCount = 0;

    // Initialize
    updateButtonState();
    setupEventListeners();
    
    // Show welcome notification after 3 seconds
    setTimeout(() => {
        if (!isOpen && messageCount === 0) {
            showNotification();
        }
    }, 3000);

    function setupEventListeners() {
        // Toggle button
        toggleBtn.addEventListener('click', toggleChatbot);
        
        // Close buttons
        mobileClose?.addEventListener('click', toggleChatbot);
        mobileBackdrop?.addEventListener('click', toggleChatbot);
        
        // Input handling
        chatInput.addEventListener('input', handleInputChange);
        chatInput.addEventListener('keypress', handleKeyPress);
        
        // Send button
        sendBtn.addEventListener('click', sendMessage);
        
        // Quick actions
        quickActions.forEach(btn => {
            btn.addEventListener('click', handleQuickAction);
        });
        
        // Window resize
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                isMobile = window.innerWidth < 768;
            }, 250);
        });
        
        // Prevent backdrop click when clicking on chat window
        chatWindow.addEventListener('click', function(e) {
            e.stopPropagation();
        });

        // Handle visibility change
        document.addEventListener('visibilitychange', function() {
            if (document.hidden && isOpen) {
                hideNotification();
            }
        });

        // Handle online/offline status
        window.addEventListener('online', function() {
            if (isOpen) {
                addMessage('Connection restored! You can continue chatting.', 'bot');
            }
        });

        window.addEventListener('offline', function() {
            if (isOpen) {
                addMessage('You appear to be offline. Messages will be sent when connection is restored.', 'bot');
            }
        });
    }

    function toggleChatbot() {
        isOpen = !isOpen;
        hideNotification();
        
        // Update ARIA attributes
        toggleBtn.setAttribute('aria-expanded', isOpen.toString());
        
        if (isOpen) {
            openChatbot();
        } else {
            closeChatbot();
        }
    }

    function openChatbot() {
        chatWindow.classList.add('show');
        chatIcon.classList.add('hidden');
        closeIcon.classList.remove('hidden');
        
        if (isMobile) {
            mobileBackdrop?.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            // Add safe area for iOS
            if (window.CSS?.supports('padding-bottom: env(safe-area-inset-bottom)')) {
                chatWindow.style.paddingBottom = 'env(safe-area-inset-bottom)';
            }
        }
        
        // Focus input after animation
        setTimeout(() => {
            if (!isMobile) {
                chatInput.focus();
            }
        }, 300);
        
        scrollToBottom();
    }

    function closeChatbot() {
        chatWindow.classList.remove('show');
        chatIcon.classList.remove('hidden');
        closeIcon.classList.add('hidden');
        
        if (isMobile) {
            mobileBackdrop?.classList.add('hidden');
            document.body.style.overflow = '';
            chatWindow.style.paddingBottom = '';
        }
        
        // Blur input to hide mobile keyboard
        chatInput.blur();
    }

    function handleInputChange(e) {
        const value = e.target.value;
        const length = value.length;
        
        // Update character counter
        charCounter.textContent = `${length}/500`;
        
        // Update button state
        updateButtonState();
        
        // Character limit warning
        if (length > 450) {
            charCounter.classList.add('text-red-500');
        } else {
            charCounter.classList.remove('text-red-500');
        }
    }

    function handleKeyPress(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    }

    function handleQuickAction(e) {
        const message = this.dataset.message;
        if (message) {
            chatInput.value = message;
            sendMessage();
        }
    }

    function updateButtonState() {
        const hasText = chatInput.value.trim().length > 0;
        sendBtn.disabled = !hasText || isLoading;
    }

    function sendMessage() {
        const message = chatInput.value.trim();
        if (!message || isLoading) return;

        // Add user message
        addMessage(message, 'user');
        chatInput.value = '';
        updateButtonState();
        updateCharCounter();
        
        // Show loading state
        setLoadingState(true);
        showTypingIndicator();
        
        // Send to server
        fetch('/chatbot', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ message: message }),
            credentials: 'same-origin'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            hideTypingIndicator();
            if (data.response) {
                addMessage(data.response, 'bot');
            } else {
                throw new Error('Invalid response format');
            }
        })
        .catch(error => {
            console.error('Chatbot Error:', error);
            hideTypingIndicator();
            
            let errorMessage = 'Sorry, I encountered an error. Please try again later.';
            
            if (error.name === 'TypeError' && error.message.includes('fetch')) {
                errorMessage = 'Connection error. Please check your internet connection and try again.';
            } else if (error.message.includes('HTTP 429')) {
                errorMessage = 'Too many requests. Please wait a moment before trying again.';
            } else if (error.message.includes('HTTP 5')) {
                errorMessage = 'Server error. Our team has been notified. Please try again later.';
            }
            
            addMessage(errorMessage, 'bot');
        })
        .finally(() => {
            setLoadingState(false);
        });
    }

    function addMessage(text, sender) {
        messageCount++;
        
        const messageDiv = document.createElement('div');
        messageDiv.className = 'flex items-start space-x-2 animate-fadeIn';
        
        if (sender === 'user') {
            messageDiv.className += ' justify-end';
            messageDiv.innerHTML = createUserMessageHTML(text);
        } else {
            messageDiv.innerHTML = createBotMessageHTML(text);
        }

        messagesContainer.appendChild(messageDiv);
        scrollToBottom();
    }

    function createUserMessageHTML(text) {
        const escapedText = escapeHtml(text);
        return `
            <div class="bg-pink-600 text-white rounded-lg p-3 shadow-sm max-w-[85%] md:max-w-xs break-words">
                <p class="text-sm">${escapedText}</p>
            </div>
            <div class="w-6 h-6 md:w-8 md:h-8 bg-gray-400 rounded-full flex items-center justify-center flex-shrink-0">
                <svg class="w-3 h-3 md:w-4 md:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
        `;
    }

    function createBotMessageHTML(text) {
        const processedText = processMarkdownText(text);
        return `
            <div class="w-6 h-6 md:w-8 md:h-8 bg-pink-500 rounded-full flex items-center justify-center flex-shrink-0">
                <svg class="w-3 h-3 md:w-4 md:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
            </div>
            <div class="bg-white rounded-lg p-3 shadow-sm max-w-[85%] md:max-w-sm border border-gray-100 break-words">
                <div class="text-sm text-gray-800 chatbot-content">${processedText}</div>
            </div>
        `;
    }

    function processMarkdownText(text) {
        // Convert markdown links [text](url) to HTML links
        text = text.replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2" class="text-pink-600 hover:text-pink-800 underline font-medium transition-colors duration-200" target="_blank" rel="noopener noreferrer">$1</a>');
        
        // Convert bold text **text** to HTML bold
        text = text.replace(/\*\*(.*?)\*\*/g, '<strong class="font-semibold text-gray-900">$1</strong>');
        
        // Convert strikethrough ~~text~~ to HTML strikethrough
        text = text.replace(/~~(.*?)~~/g, '<span class="line-through text-gray-500">$1</span>');
        
        // Convert line breaks
        text = text.replace(/\n/g, '<br>');
        
        return text;
    }

    function setLoadingState(loading) {
        isLoading = loading;
        chatInput.disabled = loading;
        
        if (loading) {
            sendIcon.classList.add('hidden');
            loadingIcon.classList.remove('hidden');
        } else {
            sendIcon.classList.remove('hidden');
            loadingIcon.classList.add('hidden');
        }
        
        updateButtonState();
    }

    function showTypingIndicator() {
        typingIndicator.classList.remove('hidden');
        scrollToBottom();
    }

    function hideTypingIndicator() {
        typingIndicator.classList.add('hidden');
    }

    function showNotification() {
        notificationBadge.classList.add('show');
    }

    function hideNotification() {
        notificationBadge.classList.remove('show');
    }

    function scrollToBottom() {
        setTimeout(() => {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }, 100);
    }

    function updateCharCounter() {
        charCounter.textContent = '0/500';
        charCounter.classList.remove('text-red-500');
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Handle mobile keyboard adjustments
    if (isMobile) {
        let initialViewportHeight = window.visualViewport ? window.visualViewport.height : window.innerHeight;
        
        function handleViewportChange() {
            if (window.visualViewport) {
                const currentHeight = window.visualViewport.height;
                const heightDifference = initialViewportHeight - currentHeight;
                
                if (heightDifference > 150 && isOpen) {
                    // Keyboard is likely open
                    messagesContainer.style.maxHeight = `${currentHeight - 200}px`;
                } else {
                    // Keyboard is likely closed
                    messagesContainer.style.maxHeight = '';
                }
                
                setTimeout(scrollToBottom, 100);
            }
        }
        
        if (window.visualViewport) {
            window.visualViewport.addEventListener('resize', handleViewportChange);
        }
        
        // Focus handling for mobile
        chatInput.addEventListener('focus', function() {
            setTimeout(() => {
                scrollToBottom();
            }, 300);
        });
    }

    // Auto-focus on desktop
    if (!isMobile && isOpen) {
        chatInput.focus();
    }
});
</script>

<!-- Chatbot Styles - CSP Compliant -->
<style>
/* Chatbot window display states */
.chatbot-window {
    display: none;
    flex-direction: column;
}

.chatbot-window.show {
    display: flex;
}

/* Notification badge display */
#notification-badge {
    display: none;
}

#notification-badge.show {
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes bounce {
    0%, 80%, 100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-10px);
    }
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

.animate-fadeIn {
    animation: fadeIn 0.3s ease-out;
}

.animate-bounce {
    animation: bounce 1s infinite;
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Scrollbar styling */
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

/* Chatbot content styling */
.chatbot-content a {
    word-break: break-word;
}

.chatbot-content strong {
    font-weight: 600;
}

/* Loading animation for send button */
@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

/* Docker deployment optimizations */
.chatbot-window.show {
    display: flex;
    will-change: transform;
}

/* Performance optimizations for containers */
#chatbot-widget {
    contain: layout style paint;
    transform: translateZ(0);
    -webkit-transform: translateZ(0);
}

.chatbot-window {
    backface-visibility: hidden;
    -webkit-backface-visibility: hidden;
}

/* Font loading optimization */
.chatbot-content {
    font-display: swap;
}

/* Mobile optimizations */
@media (max-width: 767px) {
    #chatbot-window {
        backdrop-filter: none;
        -webkit-backdrop-filter: none;
        /* Optimize for mobile containers */
        transform: translate3d(0, 0, 0);
        -webkit-transform: translate3d(0, 0, 0);
    }
    
    #chat-messages {
        padding-bottom: env(safe-area-inset-bottom, 0);
        /* Improve scrolling performance */
        -webkit-overflow-scrolling: touch;
        overscroll-behavior: contain;
    }
    
    .quick-action {
        touch-action: manipulation;
        -webkit-tap-highlight-color: transparent;
    }
    
    /* Prevent zoom on iOS when focusing input */
    #chat-input {
        font-size: 16px;
        transform: translateZ(0);
        -webkit-transform: translateZ(0);
    }
    
    /* Better mobile keyboard handling */
    .chatbot-window.show {
        height: 100vh;
        height: 100dvh; /* Dynamic viewport height for newer browsers */
    }
}

/* Smaller screen optimizations */
@media (max-width: 400px) {
    .quick-action {
        font-size: 11px;
        padding: 0.25rem 0.5rem;
    }
}

/* High DPI display optimization */
@media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
    .chatbot-window {
        border-width: 0.5px;
    }
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    #chatbot-window {
        border: 2px solid #000;
    }
    
    .bg-pink-600 {
        background-color: #000;
    }
    
    .bg-pink-500 {
        background-color: #000;
    }
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
    .animate-fadeIn,
    .animate-bounce,
    .animate-pulse,
    .animate-spin {
        animation: none;
    }
    
    .transition-all,
    .transition-colors {
        transition: none;
    }
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .bg-gray-50 {
        background-color: #1f2937;
    }
    
    .bg-white {
        background-color: #111827;
        color: #f9fafb;
    }
    
    .text-gray-800 {
        color: #f9fafb;
    }
    
    .border-gray-200 {
        border-color: #374151;
    }
    
    .border-gray-100 {
        border-color: #374151;
    }
}

/* Focus styles for accessibility */
button:focus-visible,
input:focus-visible {
    outline: 2px solid #ec4899;
    outline-offset: 2px;
}

/* Print styles */
@media print {
    #chatbot-widget {
        display: none !important;
    }
}
</style>
