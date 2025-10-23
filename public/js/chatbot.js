/**
 * Hanaya Shop Chatbot - CSP Compliant Version
 * All event handlers use addEventListener instead of inline events
 */

class HanayaChatbot {
    constructor() {
        this.isOpen = false;
        this.messages = [];
        this.responses = {
            greetings: [
                "Xin chào! Tôi là trợ lý ảo của Hanaya Shop. Tôi có thể giúp gì cho bạn?",
                "Chào bạn! Tôi ở đây để hỗ trợ bạn về sản phẩm và dịch vụ của Hanaya Shop.",
                "Xin chào! Rất vui được hỗ trợ bạn hôm nay."
            ],
            products: [
                "Hanaya Shop chuyên cung cấp hoa tươi cao cấp, quà tặng ý nghĩa và phụ kiện trang trí. Bạn quan tâm đến loại sản phẩm nào?",
                "Chúng tôi có nhiều loại hoa: hoa hồng, hoa ly, hoa tulip, và nhiều loại khác. Bạn có thể xem trong mục Sản phẩm.",
                "Các sản phẩm của chúng tôi bao gồm: hoa tươi, bó hoa, lẵng hoa, hộp quà, và phụ kiện trang trí."
            ],
            order: [
                "Để đặt hàng, bạn có thể thêm sản phẩm vào giỏ hàng và tiến hành thanh toán. Chúng tôi hỗ trợ nhiều phương thức thanh toán.",
                "Quy trình đặt hàng rất đơn giản: Chọn sản phẩm → Thêm vào giỏ → Thanh toán → Xác nhận đơn hàng.",
                "Sau khi đặt hàng thành công, bạn sẽ nhận được email xác nhận và có thể theo dõi trạng thái đơn hàng."
            ],
            delivery: [
                "Hanaya Shop giao hàng toàn quốc. Thời gian giao hàng từ 1-3 ngày tùy vào địa điểm.",
                "Chúng tôi có dịch vụ giao hàng nhanh trong ngày tại TP.HCM và Hà Nội.",
                "Phí giao hàng sẽ được tính dựa trên khoảng cách và trọng lượng đơn hàng."
            ],
            contact: [
                "Bạn có thể liên hệ với chúng tôi qua:",
                "📍 Địa chỉ: 123 Đường ABC, Quận 1, TP.HCM",
                "📞 Điện thoại: (028) 1234 5678",
                "📧 Email: info@hanayashop.com"
            ],
            default: [
                "Xin lỗi, tôi chưa hiểu câu hỏi của bạn. Bạn có thể hỏi về sản phẩm, đặt hàng, giao hàng hoặc liên hệ.",
                "Tôi có thể giúp bạn về: sản phẩm hoa tươi, cách đặt hàng, thông tin giao hàng, và thông tin liên hệ.",
                "Bạn có thể hỏi tôi về các chủ đề: sản phẩm, đặt hàng, giao hàng, thanh toán, hoặc liên hệ."
            ]
        };
        
        this.init();
    }

    init() {
        this.createChatbotHTML();
        this.bindEvents();
        this.addInitialMessage();
    }

    createChatbotHTML() {
        const chatbotHTML = `
            <!-- Chatbot Container -->
            <div id="chatbot-container" class="fixed bottom-4 right-4 z-50 hidden md:block">
                <!-- Chat Button -->
                <div id="chat-button" class="w-16 h-16 bg-gradient-to-r from-pink-500 to-purple-600 rounded-full shadow-lg cursor-pointer flex items-center justify-center transform transition-all duration-300 hover:scale-110 hover:shadow-xl animate-pulse">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>

                <!-- Chat Window -->
                <div id="chat-window" class="absolute bottom-20 right-0 w-80 h-96 bg-white rounded-lg shadow-2xl border border-gray-200 hidden flex-col overflow-hidden transform transition-all duration-300">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-pink-500 to-purple-600 text-white p-4 flex justify-between items-center">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-pink-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-sm">Hanaya Assistant</h3>
                                <p class="text-xs opacity-90">Trợ lý ảo</p>
                            </div>
                        </div>
                        <button id="close-chat" class="text-white hover:text-gray-200 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Messages Container -->
                    <div id="chat-messages" class="flex-1 p-4 overflow-y-auto space-y-4 bg-gray-50">
                        <!-- Messages will be inserted here -->
                    </div>

                    <!-- Input Area -->
                    <div class="p-4 border-t border-gray-200 bg-white">
                        <div class="flex space-x-2">
                            <input 
                                type="text" 
                                id="chat-input" 
                                placeholder="Nhập tin nhắn..." 
                                class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent text-sm"
                                maxlength="200"
                            >
                            <button 
                                id="send-message" 
                                class="px-4 py-2 bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-lg hover:from-pink-600 hover:to-purple-700 transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-pink-500"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="mt-2 flex flex-wrap gap-1">
                            <button class="quick-reply text-xs px-2 py-1 bg-gray-100 hover:bg-gray-200 rounded-full transition-colors" data-message="Sản phẩm">Sản phẩm</button>
                            <button class="quick-reply text-xs px-2 py-1 bg-gray-100 hover:bg-gray-200 rounded-full transition-colors" data-message="Đặt hàng">Đặt hàng</button>
                            <button class="quick-reply text-xs px-2 py-1 bg-gray-100 hover:bg-gray-200 rounded-full transition-colors" data-message="Giao hàng">Giao hàng</button>
                            <button class="quick-reply text-xs px-2 py-1 bg-gray-100 hover:bg-gray-200 rounded-full transition-colors" data-message="Liên hệ">Liên hệ</button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', chatbotHTML);
    }

    bindEvents() {
        // Chat button click
        document.getElementById('chat-button').addEventListener('click', () => {
            this.toggleChat();
        });

        // Close chat button
        document.getElementById('close-chat').addEventListener('click', () => {
            this.closeChat();
        });

        // Send message button
        document.getElementById('send-message').addEventListener('click', () => {
            this.sendMessage();
        });

        // Enter key to send message
        document.getElementById('chat-input').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                this.sendMessage();
            }
        });

        // Quick reply buttons
        document.querySelectorAll('.quick-reply').forEach(button => {
            button.addEventListener('click', (e) => {
                const message = e.target.getAttribute('data-message');
                this.sendUserMessage(message);
            });
        });

        // Click outside to close
        document.addEventListener('click', (e) => {
            const container = document.getElementById('chatbot-container');
            if (this.isOpen && !container.contains(e.target)) {
                this.closeChat();
            }
        });
    }

    toggleChat() {
        if (this.isOpen) {
            this.closeChat();
        } else {
            this.openChat();
        }
    }

    openChat() {
        const chatWindow = document.getElementById('chat-window');
        const chatButton = document.getElementById('chat-button');
        
        chatWindow.classList.remove('hidden');
        chatWindow.classList.add('flex');
        chatButton.classList.remove('animate-pulse');
        this.isOpen = true;

        // Focus on input
        setTimeout(() => {
            document.getElementById('chat-input').focus();
        }, 300);
    }

    closeChat() {
        const chatWindow = document.getElementById('chat-window');
        const chatButton = document.getElementById('chat-button');
        
        chatWindow.classList.add('hidden');
        chatWindow.classList.remove('flex');
        chatButton.classList.add('animate-pulse');
        this.isOpen = false;
    }

    addInitialMessage() {
        setTimeout(() => {
            this.addBotMessage(this.getRandomResponse('greetings'));
        }, 1000);
    }

    sendMessage() {
        const input = document.getElementById('chat-input');
        const message = input.value.trim();
        
        if (message) {
            this.sendUserMessage(message);
            input.value = '';
        }
    }

    sendUserMessage(message) {
        this.addUserMessage(message);
        
        // Simulate typing delay
        setTimeout(() => {
            const response = this.generateResponse(message);
            this.addBotMessage(response);
        }, 800 + Math.random() * 1200);
    }

    addUserMessage(message) {
        const messagesContainer = document.getElementById('chat-messages');
        const messageElement = document.createElement('div');
        messageElement.className = 'flex justify-end';
        messageElement.innerHTML = `
            <div class="max-w-xs bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-lg px-4 py-2 text-sm shadow-md">
                ${this.escapeHtml(message)}
            </div>
        `;
        messagesContainer.appendChild(messageElement);
        this.scrollToBottom();
    }

    addBotMessage(message) {
        const messagesContainer = document.getElementById('chat-messages');
        const messageElement = document.createElement('div');
        messageElement.className = 'flex justify-start';
        messageElement.innerHTML = `
            <div class="max-w-xs bg-white border border-gray-200 rounded-lg px-4 py-2 text-sm shadow-md">
                <div class="flex items-center space-x-2 mb-1">
                    <div class="w-4 h-4 bg-gradient-to-r from-pink-500 to-purple-600 rounded-full"></div>
                    <span class="text-xs font-medium text-gray-600">Hanaya Assistant</span>
                </div>
                <div class="text-gray-800">${this.escapeHtml(message)}</div>
            </div>
        `;
        messagesContainer.appendChild(messageElement);
        this.scrollToBottom();
    }

    scrollToBottom() {
        const messagesContainer = document.getElementById('chat-messages');
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    generateResponse(message) {
        const lowerMessage = message.toLowerCase();
        
        // Greetings
        if (this.containsKeywords(lowerMessage, ['xin chào', 'chào', 'hello', 'hi', 'hey'])) {
            return this.getRandomResponse('greetings');
        }
        
        // Products
        if (this.containsKeywords(lowerMessage, ['sản phẩm', 'hoa', 'product', 'flower', 'bán', 'có gì'])) {
            return this.getRandomResponse('products');
        }
        
        // Orders
        if (this.containsKeywords(lowerMessage, ['đặt hàng', 'order', 'mua', 'thanh toán', 'payment'])) {
            return this.getRandomResponse('order');
        }
        
        // Delivery
        if (this.containsKeywords(lowerMessage, ['giao hàng', 'delivery', 'ship', 'vận chuyển', 'nhận hàng'])) {
            return this.getRandomResponse('delivery');
        }
        
        // Contact
        if (this.containsKeywords(lowerMessage, ['liên hệ', 'contact', 'địa chỉ', 'phone', 'email', 'hotline'])) {
            return this.getRandomResponse('contact');
        }
        
        // Price
        if (this.containsKeywords(lowerMessage, ['giá', 'price', 'cost', 'bao nhiều', 'tiền'])) {
            return "Giá sản phẩm của chúng tôi rất cạnh tranh. Bạn có thể xem giá cụ thể của từng sản phẩm trong mục Sản phẩm. Nếu cần hỗ trợ thêm, vui lòng liên hệ hotline.";
        }
        
        // Quality
        if (this.containsKeywords(lowerMessage, ['chất lượng', 'quality', 'fresh', 'tươi', 'đẹp'])) {
            return "Hanaya Shop cam kết mang đến những sản phẩm hoa tươi chất lượng cao nhất. Chúng tôi nhập khẩu trực tiếp từ các vườn hoa uy tín và bảo quản trong điều kiện tối ưu.";
        }
        
        // Thanks
        if (this.containsKeywords(lowerMessage, ['cảm ơn', 'thanks', 'thank you', 'cám ơn'])) {
            return "Rất vui được hỗ trợ bạn! Nếu có thêm câu hỏi nào khác, đừng ngần ngại hỏi tôi nhé. Hanaya Shop luôn sẵn sàng phục vụ bạn! 🌸";
        }
        
        return this.getRandomResponse('default');
    }

    containsKeywords(message, keywords) {
        return keywords.some(keyword => message.includes(keyword));
    }

    getRandomResponse(category) {
        const responses = this.responses[category];
        return responses[Math.floor(Math.random() * responses.length)];
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// Initialize chatbot when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    new HanayaChatbot();
});
