{{-- Main App Layout Component - Provides consistent site layout and navigation --}}
<x-app-layout>
    {{-- Main Container - Responsive padding and centering for optimal viewing --}}
    <div class="max-w-7xl mx-auto px-2 sm:px-4 py-6 sm:py-12">
        {{-- Page Title Section - Cart icon and title for clear page identification --}}
        <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">🛒 Cart</h2>

        {{-- Main Content Area - Full height background with padding --}}
        <div class="min-h-screen bg-gray-50 py-8">
            {{-- Content Container - Constrained width for better readability --}}
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                {{-- Alert Component - Displays success/error messages from previous operations --}}
                <x-alert />

                {{-- Cart Items Display - Only show if cart contains products --}}
                @if (count($cart) > 0)
                    {{-- Checkout Form - Handles item selection and checkout process --}}
                    {{-- Form submits to checkout.preview route for validation before actual checkout --}}
                    <form id="checkout-form" method="POST" action="{{ route('checkout.preview') }}">
                        @csrf {{-- Laravel CSRF protection token for security --}}
                        {{-- Hidden input to store selected items as JSON for backend processing --}}
                        <input type="hidden" name="selected_items_json" id="selected_items_json">
                        
                        {{-- Desktop Table View - Hidden on mobile, displayed on medium+ screens --}}
                        <div class="hidden md:block">
                            {{-- Table Container - White background with shadow and rounded corners --}}
                            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden mt-8">

                            {{-- Cart Items Table - Structured layout for desktop viewing --}}
                            <table class="min-w-full">
                                {{-- Table Header - Gradient background for visual appeal --}}
                                <thead class="bg-gradient-to-r from-pink-50 to-purple-50">
                                    <tr>
                                        {{-- Select All Checkbox Column - Allows bulk selection of all items --}}
                                        <th class="py-3 px-4 text-center">
                                            <input type="checkbox" id="select-all" title="Select All">
                                        </th>
                                        {{-- Product Image Column Header --}}
                                        <th class="py-3 px-4">Image</th>
                                        {{-- Product Name Column Header --}}
                                        <th class="py-3 px-4">Product Name</th>
                                        {{-- Product Price Column Header --}}
                                        <th class="py-3 px-4">Price</th>
                                        {{-- Quantity Controls Column Header --}}
                                        <th class="py-3 px-4">Quantity</th>
                                        {{-- Line Total Column Header --}}
                                        <th class="py-3 px-4">Total</th>
                                        {{-- Actions Column Header (Delete button) --}}
                                        <th class="py-3 px-4"></th>
                                    </tr>
                                </thead>
                                {{-- Table Body - Contains all cart items --}}
                                <tbody>
                                    {{-- Total Calculation Variable - Initialize for order total calculation --}}
                                    @php $total = 0; @endphp
                                    {{-- Cart Items Loop - Display each product in cart --}}
                                    @foreach ($cart as $id => $item)
                                        {{-- Calculate running total for display - Add current item total to cart total --}}
                                        @php $total += $item['discounted_price'] * $item['quantity']; @endphp
                                        <tr class="hover:bg-pink-50 transition-all">
                                            <td class="py-3 px-4 text-center align-middle">
                                                <input type="checkbox" name="cart_ids[]" value="{{ $id }}"
                                                    class="cart-checkbox accent-pink-500 w-5 h-5"
                                                    data-price="{{ $item['discounted_price'] * $item['quantity'] }}"
                                                    data-id="{{ $id }}"
                                                    data-product-id="{{ $item['product_id'] }}">
                                            </td>
                                            <td class="py-3 px-4 align-middle">
                                                <a href="{{ route('user.products.show', $item['product_id']) }}" class="block w-16 h-16 rounded-lg overflow-hidden border-2 border-pink-200 bg-gray-100 flex items-center justify-center">
                                                    <img src="{{ $item['image_url'] && file_exists(public_path('images/products/' . $item['image_url']))
                                                        ? asset('images/products/' . $item['image_url'])
                                                        : asset('images/no-image.png') }}"
                                                        alt="{{ $item['name'] }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                                </a>
                                            </td>
                                            <td class="py-3 px-4 align-middle font-semibold text-gray-900">
                                                <a href="{{ route('user.products.show', $item['product_id']) }}" class="text-pink-700 hover:underline">
                                                    {{ $item['name'] }}
                                                </a>
                                            </td>
                                            {{-- Product Price Cell - Shows discounted price with original price strikethrough if discount exists --}}
                                            <td class="py-3 px-4 align-middle">
                                                {{-- Conditional pricing display based on discount availability --}}
                                                @if ($item['discount_percent'] > 0)
                                                    {{-- Discount Price Display - Shows both discounted and original prices --}}
                                                    <div class="space-y-1">
                                                        {{-- Discounted Price - Highlighted in pink color --}}
                                                        <div class="text-pink-600 font-bold">
                                                            ${{ number_format($item['discounted_price'], 2, '.', ',') }}
                                                        </div>
                                                        {{-- Original Price - Strikethrough to show savings --}}
                                                        <div class="text-xs text-gray-500 line-through">
                                                            ${{ number_format($item['price'], 2, '.', ',') }}
                                                        </div>
                                                    </div>
                                                @else
                                                    {{-- Regular Price Display - No discount available --}}
                                                    <div class="text-pink-600 font-bold">
                                                        ${{ number_format($item['price'], 2, '.', ',') }}
                                                    </div>
                                                @endif
                                            </td>
                                            {{-- Quantity Controls Cell - Buttons and input for quantity adjustment --}}
                                            <td class="py-3 px-4 align-middle text-center">
                                                {{-- Quantity Control Container - Flexbox layout for buttons and input --}}
                                                <div class="flex items-center justify-center gap-2">
                                                    {{-- Decrease Quantity Button - Reduces item quantity by 1 --}}
                                                    <button type="button"
                                                        class="btn-decrease bg-gray-200 hover:bg-pink-100 text-pink-600 px-2 rounded transition"
                                                        data-id="{{ $id }}">−</button>
                                                         {{-- Quantity Input Field - Manual quantity entry with data attributes for calculations --}}
                                                         <input name="quantities[{{ $id }}]" type="number" min="1"
                                                            class="quantity-input w-[80px] text-center border rounded focus:ring-pink-500"
                                                            value="{{ $item['quantity'] }}" data-id="{{ $id }}"
                                                            data-price="{{ $item['discounted_price'] }}"
                                                            data-original-price="{{ $item['price'] }}"
                                                            data-discount-percent="{{ $item['discount_percent'] }}"
                                                            data-total="{{ $item['discounted_price'] * $item['quantity']}}"
                                                            data-stock="{{ $item['product_quantity'] }}">

                                                        {{-- Increase Quantity Button - Increases item quantity by 1 --}}
                                                        <button type="button"
                                                        class="btn-increase bg-gray-200 hover:bg-pink-100 text-pink-600 px-2 rounded transition"
                                                        data-id="{{ $id }}">+</button>
                                                </div>
                                            </td>
                                            {{-- Line Total Cell - Displays total price for this item (price × quantity) --}}
                                            <td class="py-3 px-4 align-middle item-total font-bold text-purple-700"
                                                data-id="{{ $id }}">
                                                ${{ number_format($item['discounted_price'] * $item['quantity'], 2, '.', ',') }}
                                            </td>
                                            {{-- Delete Action Cell - Link to remove item from cart --}}
                                            <td class="py-3 px-4 align-middle">
                                                <a href="{{ route('cart.remove', $id) }}"
                                                    class="text-red-600 hover:underline font-medium">Delete</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    {{-- Cart Total Row - Shows grand total of all items in cart --}}
                                    <tr>
                                        {{-- Total Label - Spans multiple columns for right alignment --}}
                                        <td colspan="4" class="text-right font-bold py-3 px-4 text-lg text-gray-700">
                                            Total:</td>
                                        {{-- Total Amount - Dynamic value updated by JavaScript --}}
                                        <td colspan="2" class="font-bold py-3 px-4 text-lg text-pink-600"
                                            id="totalPrice">$0</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        {{-- Desktop Checkout Button Container - Right-aligned checkout action --}}
                        <div class="mt-8 text-right">
                            {{-- Checkout Button - Gradient background with hover effects and icon --}}
                            <button type="submit"
                                class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2">
                                {{-- Shopping Cart Icon - SVG icon for visual identification --}}
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                                Checkout
                            </button>
                        </div>
                    </div>
                    {{-- Mobile Card Layout - Displays on small screens, hidden on desktop --}}
                    <div class="md:hidden space-y-6 mt-8">
                        {{-- Mobile Select All Card - Prominent selection option for mobile users --}}
                        <div
                            class="bg-white rounded-xl shadow-lg p-4 flex items-center gap-3 border-l-4 border-pink-500">
                            {{-- Mobile Select All Checkbox - Synchronized with desktop version --}}
                            <input type="checkbox" id="select-all-mobile" title="Select All"
                                class="accent-pink-500 w-5 h-5">
                            {{-- Select All Label - Clear text for user understanding --}}
                            <span class="font-semibold text-gray-700">Select All</span>
                        </div>
                        {{-- Mobile Cart Items Loop - Card-based layout for each product --}}
                        @foreach ($cart as $id => $item)
                            {{-- Mobile Product Card - Individual item container with shadow and border --}}
                            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-4 flex flex-col gap-3">
                                {{-- Mobile Card Top Section - Checkbox, image, and product info --}}
                                <div class="flex items-center gap-3">
                                    {{-- Item Selection Checkbox - Same functionality as desktop version --}}
                                    <input type="checkbox" name="cart_ids[]" value="{{ $id }}"
                                        class="cart-checkbox accent-pink-500 w-5 h-5"
                                        data-price="{{ $item['discounted_price'] * $item['quantity'] }}"
                                        data-id="{{ $id }}" data-product-id="{{ $item['product_id'] }}">
                                    {{-- Mobile Product Image Container - Larger size for mobile viewing --}}
                                    <div
                                        class="w-20 h-20 rounded-lg overflow-hidden border-2 border-pink-200 bg-gray-100 flex items-center justify-center">
                                        {{-- Product Image - Same logic as desktop version with fallback --}}
                                        <img src="{{ $item['image_url'] && file_exists(public_path('images/products/' . $item['image_url']))
                                            ? asset('images/products/' . $item['image_url'])
                                            : asset('images/no-image.png') }}"
                                            alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                                    </div>
                                    {{-- Mobile Product Info Section - Name and pricing information --}}
                                    <div class="flex-1">
                                        {{-- Product Name - Bold text for clear identification --}}
                                        <div class="font-semibold text-gray-900">{{ $item['name'] }}</div>
                                        {{-- Mobile Price Display - Conditional discount pricing --}}
                                        @if ($item['discount_percent'] > 0)
                                            {{-- Discounted Price Layout - Shows savings clearly on mobile --}}
                                            <div class="space-y-1">
                                                {{-- Current Discounted Price - Highlighted in pink --}}
                                                <div class="text-pink-600 font-bold">
                                                    ${{ number_format($item['discounted_price'], 2, '.', ',') }}
                                                </div>
                                                {{-- Original Price - Smaller text with strikethrough --}}
                                                <div class="text-xs text-gray-500 line-through">
                                                    ${{ number_format($item['price'], 2, '.', ',') }}
                                                </div>
                                            </div>
                                        @else
                                            {{-- Regular Price - No discount available --}}
                                            <div class="text-pink-600 font-bold">
                                                ${{ number_format($item['price'], 2, '.', ',') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                {{-- Mobile Card Bottom Section - Quantity controls, total, and delete action --}}
                                <div class="flex items-center justify-between">
                                    {{-- Mobile Quantity Controls - Compact layout for touch interaction --}}
                                    <div class="flex items-center gap-2">
                                        {{-- Decrease Button - Touch-friendly size for mobile --}}
                                        <button type="button"
                                            class="btn-decrease bg-gray-200 hover:bg-pink-100 text-pink-600 px-2 rounded transition"
                                            data-id="{{ $id }}">−</button>
                                        {{-- Mobile Quantity Input - Smaller width for mobile layout --}}
                                        <input type="number" min="1"
                                            class="quantity-input w-[60px] text-center border rounded focus:ring-pink-500"
                                            value="{{ $item['quantity'] }}" data-id="{{ $id }}"
                                            data-price="{{ $item['discounted_price'] }}"
                                            data-original-price="{{ $item['price'] }}"
                                            data-discount-percent="{{ $item['discount_percent'] }}"
                                            data-total="{{ $item['discounted_price'] * $item['quantity']}}"
                                            data-stock="{{ $item['product_quantity'] }}">
                                        {{-- Increase Button - Touch-friendly size for mobile --}}
                                        <button type="button"
                                            class="btn-increase bg-gray-200 hover:bg-pink-100 text-pink-600 px-2 rounded transition"
                                            data-id="{{ $id }}">+</button>
                                    </div>
                                    {{-- Mobile Item Total - Real-time updated line total --}}
                                    <div class="item-total font-bold text-purple-700" data-id="{{ $id }}">
                                        ${{ number_format($item['discounted_price'] * $item['quantity'], 2, '.', ',') }}
                                    </div>
                                    {{-- Mobile Delete Action - Remove item from cart --}}
                                    <a href="{{ route('cart.remove', $id) }}"
                                        class="text-red-600 hover:underline font-medium">Delete</a>
                                </div>
                            </div>
                        @endforeach
                        {{-- Mobile Cart Total Card - Grand total display for mobile users --}}
                        <div
                            class="bg-white rounded-xl shadow-lg p-4 flex justify-between items-center font-bold border-l-4 border-purple-500">
                            {{-- Total Label - Clear identification of cart total --}}
                            <span class="text-gray-700">Total:</span>
                            {{-- Total Amount - Dynamic value updated by JavaScript --}}
                            <span id="totalPrice" class="text-pink-600">$0</span>
                        </div>
                        {{-- Mobile Checkout Button Container - Full-width layout for mobile --}}
                        <div class="text-right mt-4">
                            {{-- Mobile Checkout Button - Same styling as desktop version --}}
                            <button type="submit"
                                class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2">
                                {{-- Checkout Icon - SVG shopping cart icon --}}
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                                Checkout
                            </button>
                        </div>
                    </div>
                </form>
            @else
                {{-- Empty Cart State - Displays when no items in cart --}}
                <div class="text-center py-16">
                    {{-- Empty Cart Icon Container - Large circular background with cart icon --}}
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        {{-- Empty Cart Icon - SVG shopping cart illustration --}}
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    {{-- Empty Cart Title - Clear message about cart status --}}
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Your cart is empty</h3>
                    {{-- Empty Cart Description - Encouraging message to start shopping --}}
                    <p class="text-gray-500 mb-6">You haven't added any products to your cart yet. Start shopping now!</p>
                    {{-- Call-to-Action Button - Directs user to product catalog --}}
                    <a href="{{ route('user.products.index') }}"
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2">
                        {{-- Shopping Icon - Encourages browsing products --}}
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        Shopping Now
                    </a>
                </div>
            @endif
        </div>
    </div>
    {{-- 
    JavaScript Section - Cart Functionality and Interactions
    
    This script handles all client-side cart functionality including:
    - Real-time total calculations
    - Quantity adjustments with +/- buttons
    - Select all/none functionality for both desktop and mobile
    - Form data preparation for checkout submission
    - Currency formatting for consistent display
    - Validation and error handling
    --}}
    <script>
        {{-- Document Ready Handler - Initialize cart functionality when page loads --}}
        document.addEventListener("DOMContentLoaded", function() {
            {{-- DOM Element References - Get key elements for cart functionality --}}
            const totalPriceEl = document.getElementById('totalPrice');           // Total display elements
            const selectAllCheckbox = document.getElementById('select-all');      // Desktop select all checkbox
            const selectAllMobile = document.getElementById('select-all-mobile'); // Mobile select all checkbox
            const checkoutForm = document.getElementById('checkout-form');        // Main checkout form

            {{-- 
            Currency Formatting Function
            Formats numbers as Vietnamese locale with USD suffix for consistent display
            @param {number} value - The numeric value to format
            @returns {string} Formatted currency string
            --}}
            function formatCurrency(value) {
                return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(value);
            }

            {{-- 
            Update Total Calculation Function
            Calculates and updates the total price based on selected items and their quantities
            Prevents duplicate counting by tracking processed item IDs
            Updates all total display elements (desktop and mobile)
            --}}
            function updateTotal() {
                let total = 0;                    // Running total accumulator
                const checkedIds = new Set();    // Track processed items to prevent duplicates
                
                // Loop through all checked cart item checkboxes
                document.querySelectorAll('.cart-checkbox:checked').forEach(cb => {
                    const id = cb.dataset.id;    // Get item ID from checkbox data
                    if (checkedIds.has(id)) return; // Skip if already processed (prevent duplicates)
                    checkedIds.add(id);           // Mark as processed
                    
                    // Get quantity input and price data for calculation
                    const qtyInput = document.querySelector(`.quantity-input[data-id="${id}"]`);
                    const price = parseFloat(qtyInput.dataset.price);    // Unit price
                    const quantity = parseInt(qtyInput.value);           // Current quantity
                    total += price * quantity;    // Add to running total
                });
                
                // Update all total price display elements (multiple elements for responsive design)
                document.querySelectorAll('#totalPrice').forEach(el => {
                    el.textContent = formatCurrency(total);
                });
            }

            {{-- 
            Update Checkbox Price Data Function
            Updates the data-price attribute of checkboxes when quantities change
            This ensures accurate total calculations after quantity modifications
            @param {string} id - The cart item ID
            @param {number} newTotal - The new total price for this item
            --}}
            function updateCheckboxPrice(id, newTotal) {
                const cb = document.querySelector(`.cart-checkbox[data-id="${id}"]`);
                if (cb) {
                    cb.dataset.price = newTotal; // Update checkbox price data for calculations
                }
            }

            {{-- 
            Update Item Total Display Function
            Updates the displayed line total for a specific cart item
            @param {string} id - The cart item ID
            @param {number} quantity - Current quantity
            @param {number} price - Unit price
            --}}
            function updateItemTotal(id, quantity, price) {
                const totalCell = document.querySelector(`.item-total[data-id="${id}"]`);
                if (totalCell) {
                    const total = quantity * price;  // Calculate line total
                    totalCell.textContent = formatCurrency(total); // Update display
                }
            }

            {{-- Quantity Increase Button Event Handlers - Handle + button clicks --}}
            document.querySelectorAll('.btn-increase').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;  // Get item ID from button data
                    const input = document.querySelector(`.quantity-input[data-id="${id}"]`);
                    let quantity = parseInt(input.value);
                    quantity++;                   // Increment quantity
                    input.value = quantity;       // Update input value
                    
                    // Update all related calculations and displays
                    updateCheckboxPrice(id, quantity * parseFloat(input.dataset.price));
                    updateItemTotal(id, quantity, parseFloat(input.dataset.price));
                    updateTotal(); // Recalculate cart total
                });
            });

            {{-- Quantity Decrease Button Event Handlers - Handle - button clicks --}}
            document.querySelectorAll('.btn-decrease').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;  // Get item ID from button data
                    const input = document.querySelector(`.quantity-input[data-id="${id}"]`);
                    let quantity = parseInt(input.value);
                    if (quantity > 1) {           // Prevent quantity from going below 1
                        quantity--;               // Decrement quantity
                        input.value = quantity;   // Update input value
                        
                        // Update all related calculations and displays
                        updateCheckboxPrice(id, quantity * parseFloat(input.dataset.price));
                        updateItemTotal(id, quantity, parseFloat(input.dataset.price));
                        updateTotal(); // Recalculate cart total
                    }
                });
            });

            {{-- Manual Quantity Input Event Handlers - Handle direct input changes --}}
            document.querySelectorAll('.quantity-input').forEach(input => {
                input.addEventListener('input', function() {
                    const id = this.dataset.id;      // Get item ID from input data
                    const quantity = parseInt(this.value);
                    if (quantity >= 1) {              // Validate minimum quantity
                        // Update all related calculations and displays
                        updateCheckboxPrice(id, quantity * parseFloat(this.dataset.price));
                        updateItemTotal(id, quantity, parseFloat(this.dataset.price));
                        updateTotal(); // Recalculate cart total
                    }
                });
            });

            {{-- Desktop Select All Functionality - Handle desktop select all checkbox --}}
            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    const isChecked = this.checked;  // Get current checked state
                    // Update all individual item checkboxes to match select all state
                    document.querySelectorAll('.cart-checkbox').forEach(cb => {
                        cb.checked = isChecked;
                    });
                    // Synchronize mobile select all checkbox
                    if (selectAllMobile) selectAllMobile.checked = isChecked;
                    updateTotal(); // Recalculate total with new selections
                });
            }

            {{-- Mobile Select All Functionality - Handle mobile select all checkbox --}}
            if (selectAllMobile) {
                selectAllMobile.addEventListener('change', function() {
                    const isChecked = this.checked;  // Get current checked state
                    // Update all individual item checkboxes to match select all state
                    document.querySelectorAll('.cart-checkbox').forEach(cb => {
                        cb.checked = isChecked;
                    });
                    // Synchronize desktop select all checkbox
                    if (selectAllCheckbox) selectAllCheckbox.checked = isChecked;
                    updateTotal(); // Recalculate total with new selections
                });
            }

            {{-- Individual Checkbox Change Handlers - Update select all status when individual items change --}}
            document.querySelectorAll('.cart-checkbox').forEach(cb => {
                cb.addEventListener('change', function() {
                    // Check if all checkboxes are selected to update select all status
                    const allCheckboxes = document.querySelectorAll('.cart-checkbox');
                    const allChecked = [...allCheckboxes].every(c => c.checked);
                    
                    // Update both desktop and mobile select all checkboxes
                    if (selectAllCheckbox) selectAllCheckbox.checked = allChecked;
                    if (selectAllMobile) selectAllMobile.checked = allChecked;
                    updateTotal(); // Recalculate total with new selections
                });
            });

            {{-- 
            Form Submission Handler - Prepare checkout data when form is submitted
            Collects all selected items with their details and packages them as JSON
            for backend processing in the checkout flow
            --}}
            checkoutForm.addEventListener('submit', function(e) {
                const checkedCheckboxes = document.querySelectorAll('.cart-checkbox:checked');
                const selectedItems = [];        // Array to hold selected item data
                const checkedIds = new Set();    // Prevent duplicate processing
                
                // Process each checked checkbox to extract item data
                document.querySelectorAll('.cart-checkbox:checked').forEach(cb => {
                    const id = cb.dataset.id;
                    
                    if (checkedIds.has(id)) return; // Skip if already processed
                    checkedIds.add(id);              // Mark as processed
                    
                    // Find the containing row or card element
                    let row = cb.closest('tr');      // Try desktop table row first
                    if (!row) {
                        row = cb.closest('.bg-white'); // Fall back to mobile card
                    }
                    
                    // Extract product image URL
                    const image = row.querySelector('img').getAttribute('src');
                    
                    // Extract product name from different possible locations
                    let name = '';
                    const nameTd = row.querySelector('td:nth-child(3)'); // Desktop table cell
                    if (nameTd) {
                        name = nameTd.textContent.trim();
                    } else {
                        const nameDiv = row.querySelector('.font-semibold'); // Mobile card element
                        name = nameDiv ? nameDiv.textContent.trim() : '';
                    }
                    
                    // Extract pricing and quantity data from input attributes
                    const price = parseFloat(row.querySelector('.quantity-input').dataset.price);        // Current/discounted price
                    const originalPrice = parseFloat(row.querySelector('.quantity-input').dataset.originalPrice); // Original price
                    const discountPercent = parseFloat(row.querySelector('.quantity-input').dataset.discountPercent) || 0; // Discount percentage
                    const quantity = parseInt(row.querySelector('.quantity-input').value);               // Selected quantity
                    const subtotal = price * quantity;                                                   // Line total
                    const stock_quantity = parseInt(row.querySelector('.quantity-input').dataset.stock); // Available stock
                    
                    // Package item data for checkout processing
                    selectedItems.push({
                        cart_id: cb.dataset.id,           // Cart item ID for removal after checkout
                        id: cb.dataset.productId,         // Product ID for order details
                        image,                             // Product image URL
                        name,                              // Product name
                        price: originalPrice,              // Original price (for order history)
                        discounted_price: price,           // Current/discounted price
                        discount_percent: discountPercent, // Discount percentage
                        quantity,                          // Ordered quantity
                        subtotal,                          // Line total
                        stock_quantity                     // Available stock for validation
                    });
                });
                
                // Store selected items as JSON in hidden form field for backend processing
                document.getElementById('selected_items_json').value = JSON.stringify(selectedItems);
            });

            {{-- Initialize total calculation on page load --}}
            updateTotal();
        });
    </script>

{{-- End of main app layout --}}
</x-app-layout>
