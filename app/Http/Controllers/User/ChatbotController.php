<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ChatbotController extends Controller
{
    /**
     * Handle chatbot conversation
     */
    public function chat(Request $request): JsonResponse
    {
        try {
            $message = $request->input('message');
            
            if (empty($message)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Message is required'
                ], 400);
            }

            // Simple chatbot responses based on keywords
            $response = $this->generateResponse($message);

            return response()->json([
                'success' => true,
                'response' => $response
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.'
            ], 500);
        }
    }

    /**
     * Generate chatbot response based on user message
     */
    private function generateResponse(string $message): string
    {
        $message = strtolower(trim($message));
        
        // Greeting responses
        if (preg_match('/\b(hello|hi|hey|good morning|good afternoon|good evening|xin chào|chào|chào bạn)\b/i', $message)) {
            $greetings = [
                "Hello! Welcome to Hanaya Shop! 🌸 How can I help you today?",
                "Hi there! I'm here to assist you with your flower and gift shopping! 💐",
                "Welcome to Hanaya Shop! Looking for something special? I'm here to help! 🌺"
            ];
            return $greetings[array_rand($greetings)];
        }

        // Product inquiries
        if (preg_match('/\b(product|flower|soap flower|gift|souvenir|price|buy|purchase)\b/i', $message)) {
            return "🌸 We have a wonderful collection of products at Hanaya Shop:\n\n" .
                   "• **Soap Flowers** - Beautiful and fragrant soap flowers that last forever\n" .
                   "• **Fresh Flowers** - Beautiful bouquets for special occasions\n" .
                   "• **Gifts & Souvenirs** - Meaningful gifts for your loved ones\n\n" .
                   "You can browse our products by visiting our [Products Page](/products). Would you like to know more about any specific category?";
        }

        // Shipping and delivery
        if (preg_match('/\b(shipping|delivery|ship|deliver|how long|when will)\b/i', $message)) {
            return "🚚 **Shipping Information:**\n\n" .
                   "• Standard shipping fee: $8\n" .
                   "• Processing time: 1-2 business days\n" .
                   "• Delivery time: 3-5 business days\n" .
                   "• Free packaging and care instructions included\n\n" .
                   "We ensure your flowers and gifts arrive in perfect condition!";
        }

        // Orders and tracking
        if (preg_match('/\b(order|track|status|my order|order status)\b/i', $message)) {
            return "📦 **Order Management:**\n\n" .
                   "• You can track your orders in [My Orders](/orders)\n" .
                   "• Order status updates are sent via email\n" .
                   "• For order changes, please contact us within 24 hours\n\n" .
                   "Need help with a specific order? Please provide your order number!";
        }

        // Care instructions
        if (preg_match('/\b(care|how to|maintain|keep|preserve|last)\b/i', $message)) {
            return "🌺 **Care Instructions:**\n\n" .
                   "**For Soap Flowers:**\n" .
                   "• Keep in a dry place\n" .
                   "• Avoid direct sunlight\n" .
                   "• Can be used as decoration or actual soap\n\n" .
                   "**For Fresh Flowers:**\n" .
                   "• Change water every 2-3 days\n" .
                   "• Trim stems at an angle\n" .
                   "• Keep away from direct heat\n\n" .
                   "Need specific care tips? Let me know what type of flowers you have!";
        }

        // Contact and support
        if (preg_match('/\b(contact|phone|email|address|location|hours|open)\b/i', $message)) {
            return "📞 **Contact Information:**\n\n" .
                   "• **Phone:** " . config('constants.shop_phone') . "\n" .
                   "• **Email:** " . config('constants.shop_email') . "\n" .
                   "• **Address:** " . config('constants.shop_address') . "\n" .
                   "• **Hours:** Monday - Sunday: 8:00 AM - 10:00 PM\n\n" .
                   "Feel free to reach out anytime! We're here to help! 💐";
        }

        // Payment methods
        if (preg_match('/\b(payment|pay|card|cash|method)\b/i', $message)) {
            return "💳 **Payment Methods:**\n\n" .
                   "We accept various payment methods for your convenience:\n" .
                   "• Credit/Debit Cards (Visa, MasterCard)\n" .
                   "• Cash on Delivery (COD)\n" .
                   "• Bank Transfer\n\n" .
                   "All transactions are secure and protected! 🔒";
        }

        // About the shop
        if (preg_match('/\b(about|story|why|choose|quality|guarantee)\b/i', $message)) {
            return "🌸 **About Hanaya Shop:**\n\n" .
                   "We're passionate about bringing beauty and joy through flowers and meaningful gifts. " .
                   "Our specialties include:\n\n" .
                   "• High-quality soap flowers that last forever\n" .
                   "• Fresh, beautiful flower arrangements\n" .
                   "• Unique gifts and souvenirs\n" .
                   "• Excellent customer service\n\n" .
                   "Visit our [About Page](/about) to learn more about our story! 💕";
        }

        // Thanks and goodbye
        if (preg_match('/\b(thank|thanks|bye|goodbye|see you)\b/i', $message)) {
            $goodbyes = [
                "You're welcome! Thank you for choosing Hanaya Shop! 🌸 Have a wonderful day!",
                "Happy to help! Come back anytime! 💐 Goodbye!",
                "Thank you for visiting Hanaya Shop! Take care! 🌺"
            ];
            return $goodbyes[array_rand($goodbyes)];
        }

        // Default response
        $defaultResponses = [
            "I'd love to help you! Could you please be more specific about what you're looking for? 🌸\n\nI can help you with:\n• Product information\n• Shipping details\n• Order status\n• Care instructions\n• Contact information",
            "I'm here to assist you with anything related to Hanaya Shop! 💐\n\nFeel free to ask about our flowers, gifts, shipping, or any other questions you might have!",
            "Thank you for your question! 🌺\n\nI can help you with products, orders, shipping, care instructions, and more. What would you like to know?"
        ];

        return $defaultResponses[array_rand($defaultResponses)];
    }
}