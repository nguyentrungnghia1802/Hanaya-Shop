<?php
/**
 * Admin Users Controller
 * 
 * This controller handles user management functionality for the admin panel
 * in the Hanaya Shop e-commerce application. It provides comprehensive CRUD
 * operations for user accounts, including creation, editing, deletion, and
 * detailed user information viewing.
 * 
 * Key Features:
 * - User listing with pagination and search functionality
 * - Bulk user creation with validation
 * - Individual user editing and updates
 * - Secure user deletion with safety checks
 * - User detail views with orders and cart information
 * - Cache management for performance optimization
 * - Role-based access control and security
 * - AJAX support for seamless user experience
 * 
 * Security Features:
 * - Prevents admin self-modification/deletion
 * - Role validation and authorization
 * - Input sanitization and validation
 * - Password encryption for user security
 * 
 * @package App\Http\Controllers\Admin
 * @author Hanaya Shop Development Team
 * @version 1.0
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;         // HTTP request handling
use App\Models\User;                 // User model for database operations
use Illuminate\Support\Facades\Cache; // Cache management for performance
use App\Models\Order\Order;          // Order model for user statistics
use App\Models\Cart\Cart;            // Cart model for user activity
use Illuminate\Support\Facades\Auth; // Authentication services

/**
 * Users Controller Class
 * 
 * Manages all user-related administrative functions including account creation,
 * modification, deletion, and detailed user analytics. Implements security
 * measures to prevent unauthorized access and self-modification.
 */
class UsersController extends Controller
{
    /**
     * Display User List with Pagination
     * 
     * Shows a paginated list of all users excluding the current admin.
     * This prevents the admin from accidentally modifying or deleting their own account.
     * Uses pagination instead of caching to ensure real-time data accuracy for admin operations.
     * 
     * Performance Considerations:
     * - Pagination (20 users per page) for better page load times
     * - Excludes current admin for security and UX
     * - No caching for real-time admin data accuracy
     * 
     * @return \Illuminate\View\View User index view with paginated users
     */
    public function index()
    {
        // User List Retrieval with Security Filter
        /**
         * Paginated User Query - Exclude current admin from user list
         * Security measure prevents admin from modifying themselves
         * Pagination improves performance for large user databases
         */
        $users = User::where('id', '!=', Auth::id())->paginate(20); // 20 users per page, exclude current admin
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show User Creation Form
     * 
     * Displays the form for creating new user accounts.
     * Supports both single and bulk user creation for administrative efficiency.
     * Form includes all necessary fields with proper validation rules.
     * 
     * @return \Illuminate\View\View User creation form view
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store Multiple New Users
     * 
     * Handles bulk user creation from the admin panel.
     * Validates all user data, encrypts passwords, and creates multiple accounts simultaneously.
     * Clears cache after creation to ensure fresh data on subsequent requests.
     * 
     * Validation Rules:
     * - users: Required array with at least one user
     * - name: Required string, maximum 255 characters
     * - email: Required, valid email format, must be unique
     * - password: Required string, minimum 6 characters
     * - role: Required, must be either 'user' or 'admin'
     * 
     * Security Features:
     * - Password encryption using bcrypt
     * - Email uniqueness validation
     * - Role validation to prevent unauthorized role assignment
     * 
     * @param \Illuminate\Http\Request $request HTTP request with user data array
     * @return \Illuminate\Http\RedirectResponse Redirect to user list with success message
     */
    public function store(Request $request)
    {
        // Input Validation for User Array
        /**
         * Bulk User Validation - Comprehensive validation for multiple users
         * Ensures data integrity and security for all created accounts
         * Array validation allows bulk creation while maintaining data quality
         */
        $request->validate([
            'users' => 'required|array|min:1',                    // Must have at least one user
            'users.*.name' => 'required|string|max:255',          // Name validation for each user
            'users.*.email' => 'required|email|unique:users,email', // Email validation with uniqueness check
            'users.*.password' => 'required|string|min:6',        // Password strength requirement
            'users.*.role' => 'required|in:user,admin',           // Role validation for security
        ]);

        // Bulk User Creation Loop
        /**
         * User Creation Process - Create multiple user accounts with security
         * Password encryption and role assignment for each user
         * Batch creation for administrative efficiency
         */
        foreach ($request->input('users') as $userData) {
            User::create([
                'name' => $userData['name'],                // User display name
                'email' => $userData['email'],              // Unique email address
                'password' => bcrypt($userData['password']), // Encrypted password for security
                'role' => $userData['role'],                // User role (user/admin)
            ]);
        }

        // Cache Management
        /**
         * Cache Invalidation - Clear cached user data after creation
         * Ensures fresh data on subsequent admin panel requests
         * Prevents stale data from affecting admin operations
         */
        Cache::forget('admin_users_all');

        return redirect()->route('admin.user')->with('success', __('admin.account_created_successfully'));
    }

    /**
     * Show User Edit Form
     * 
     * Displays the form for editing an existing user account.
     * Includes security check to prevent admin self-modification.
     * Pre-populates form with current user data for easy editing.
     * 
     * Security Features:
     * - Prevents admin from editing their own account
     * - User existence validation
     * - Role-based access control
     * 
     * @param int $id User ID to edit
     * @return \Illuminate\View\View User edit form with current data
     */
    public function edit($id)
    {
        // Security Check - Prevent Self-Modification
        /**
         * Admin Self-Modification Prevention - Security measure
         * Prevents admin from accidentally modifying their own account
         * Maintains system integrity and prevents privilege escalation issues
         */
        if ($id == Auth::id()) {
            abort(403, __('admin.you_cannot_edit_yourself'));
        }
        
        $user = User::findOrFail($id); // Find user or throw 404 error
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update User Information
     * 
     * Updates user account information with comprehensive validation.
     * Supports optional password updates and role changes.
     * Includes security measures and cache management.
     * 
     * Update Features:
     * - Name and email modification
     * - Role changes with validation
     * - Optional password updates
     * - Email uniqueness validation (excluding current user)
     * 
     * Security Features:
     * - Self-modification prevention
     * - Password encryption when updated
     * - Role validation
     * - Input sanitization
     * 
     * @param \Illuminate\Http\Request $request HTTP request with updated user data
     * @param int $id User ID to update
     * @return \Illuminate\Http\RedirectResponse Redirect to user list with success message
     */
    public function update(Request $request, $id)
    {
        // Security Check - Prevent Self-Updates
        /**
         * Admin Self-Update Prevention - Critical security measure
         * Prevents admin from modifying their own account details
         * Maintains administrative account integrity
         */
        if ($id == Auth::id()) {
            abort(403, __('admin.you_cannot_edit_yourself'));
        }
        
        $user = User::findOrFail($id);

        // Input Validation for User Updates
        /**
         * User Update Validation - Comprehensive validation for user modifications
         * Email uniqueness excludes current user to allow keeping same email
         * Password is optional to allow updates without changing password
         */
        $request->validate([
            'name' => 'required|string|max:255',                      // Name validation
            'email' => 'required|email|unique:users,email,' . $user->getKey(), // Email with uniqueness exception
            'role' => 'required|in:user,admin',                       // Role validation
            'password' => 'nullable|string|min:6',                    // Optional password update
        ]);

        // User Information Updates
        /**
         * User Data Update Process - Update user information selectively
         * Password only updated if provided to maintain security
         * All other fields updated with validated data
         */
        $user->name = $request->input('name');   // Update display name
        $user->email = $request->input('email'); // Update email address
        $user->role = $request->input('role');   // Update user role

        // Conditional Password Update
        /**
         * Password Update Logic - Only update password if new one provided
         * Maintains existing password if field is empty
         * Encrypts new password for security
         */
        if ($request->filled('password')) {
            $user->password = bcrypt($request->input('password')); // Encrypt new password
        }

        $user->save(); // Save all changes to database

        // Cache Management
        /**
         * Cache Invalidation - Clear user cache after updates
         * Ensures updated data appears immediately in admin interface
         * Prevents stale data from affecting subsequent operations
         */
        Cache::forget('admin_users_all');

        return redirect()->route('admin.user')->with('success', __('admin.account_updated_successfully'));
    }

    /**
     * Delete Multiple Users
     * 
     * Handles bulk user deletion with security measures and AJAX support.
     * Prevents admin from deleting their own account and provides flexible response types.
     * Supports both regular form submissions and AJAX requests.
     * 
     * Security Features:
     * - Admin self-deletion prevention
     * - ID validation and sanitization
     * - Role-based access control
     * 
     * Response Types:
     * - JSON response for AJAX requests
     * - Redirect response for form submissions
     * 
     * @param \Illuminate\Http\Request $request HTTP request with user IDs to delete
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse Appropriate response based on request type
     */
    public function destroy(Request $request)
    {
        // ID Processing and Security Filter
        /**
         * User ID Processing - Handle both single and multiple user deletions
         * Security filter removes admin's own ID to prevent self-deletion
         * Array normalization ensures consistent processing
         */
        $ids = $request->input('ids', []);
        if (!is_array($ids)) $ids = [$ids];           // Normalize to array format
        $ids = array_diff($ids, [Auth::id()]);        // Remove admin's own ID for security
        
        $blockedUsers = [];
        $deletableIds = [];
        
        foreach ($ids as $id) {
            $user = User::find($id);
            if (!$user) continue;

            $orders = $user->order()
                ->whereIn('status', ['pending', 'processing', 'shipped'])
                ->count();

            if ($orders > 0) {
                $blockedUsers[] = $user->email;
            } else {
                $deletableIds[] = $id;
            }
        }

        if (!empty($deletableIds)) {
            User::whereIn('id', $deletableIds)->delete();
        }

        if (!empty($blockedUsers)) {
            return response()->json([
                'success' => false,
                'message' => __('admin.cannot_delete_user_with_active_orders'),
                'blocked' => $blockedUsers
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => __('admin.message_selected_account_delete')
        ]);


        // Cache Management
        /**
         * Cache Invalidation - Clear user cache after deletions
         * Ensures deleted users disappear immediately from admin interface
         * Maintains data consistency across admin panel
         */
        Cache::forget('admin_users_all');

        // Response Type Handling
        /**
         * Dynamic Response - Return appropriate response type
         * JSON for AJAX requests (seamless UI updates)
         * Redirect for traditional form submissions
         */
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true]); // AJAX success response
        }

        return redirect()->route('admin.user')->with('success', __('admin.account_deleted_successfully'));
    }

    /**
     * Delete Single User by ID
     * 
     * Deletes a specific user account using route parameter ID.
     * Includes security measures and supports both AJAX and traditional requests.
     * Complementary method to bulk deletion for single-user operations.
     * 
     * Security Features:
     * - Self-deletion prevention
     * - User existence validation
     * - Role-based access control
     * 
     * @param int $id User ID to delete
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse Appropriate response based on request type
     */
    public function destroySingle($id)
    {
        // Security Check - Prevent Self-Deletion
        /**
         * Admin Self-Deletion Prevention - Critical security measure
         * Prevents admin from accidentally deleting their own account
         * Maintains administrative access to the system
         */
        if ($id == Auth::id()) {
            abort(403, __('admin.you_cannot_delete_yourself'));
        }

        $user = User::findOrFail($id); // Find user or return 404

        $orders = $user->order()->whereIn('status', ['pending', 'processing', 'shipped'])->count();

        if ($orders > 0) {
            return response()->json([
                'success' => false,
                'message' => __('admin.cannot_delete_user_with_active_orders')
            ]);
        }
        $user->delete();            // Delete user account

        // Cache Management
        /**
         * Cache Invalidation - Clear user cache after single deletion
         * Ensures consistency with bulk deletion behavior
         * Maintains fresh data in admin interface
         */
        Cache::forget('admin_users_all');

        // Response Type Handling
        /**
         * Dynamic Response - Support both AJAX and traditional requests
         * Consistent behavior with bulk deletion method
         * Seamless user experience regardless of interaction method
         */
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true]); // AJAX success response
        }

        return redirect()->route('admin.user')->with('success', __('admin.account_deleted_successfully'));
    }

    /**
     * Show User Details with Related Data
     * 
     * Displays comprehensive user information including orders and cart contents.
     * Provides admin insight into user activity and purchasing behavior.
     * Useful for customer service and user account analysis.
     * 
     * Related Data:
     * - User orders with order details
     * - Cart contents with product information
     * - User account information
     * 
     * @param int $id User ID to display
     * @return \Illuminate\View\View User detail view with related data
     */
    public function show($id)
    {
        $user = User::findOrFail($id); // Find user or return 404

        // User Orders Retrieval
        /**
         * Order History - Get all orders for this user
         * Provides insight into user purchasing behavior
         * Useful for customer service and account analysis
         */
        $orders = $user->order()->get();

        // User Cart Retrieval
        /**
         * Cart Contents - Get current cart items with product details
         * Eager loading product information for complete view
         * Shows current user shopping intentions
         */
        $carts = $user->cart()->with('product')->get();

        return view('admin.users.show', compact('user', 'orders', 'carts'));
    }

    /**
     * Search Users by Name or Email
     * 
     * Provides AJAX-powered search functionality for the user management interface.
     * Searches across user names and email addresses for flexible user discovery.
     * Returns HTML table rows for seamless integration with existing interface.
     * 
     * Search Features:
     * - Name and email search
     * - Partial match support with LIKE queries
     * - Excludes current admin from results
     * - HTML response for direct DOM insertion
     * 
     * @param \Illuminate\Http\Request $request HTTP request with search query
     * @return \Illuminate\Http\JsonResponse JSON response with HTML table rows
     */
    public function search(Request $request)
    {
        $query = $request->input('query', ''); // Get search query parameter

        // User Search Query
        /**
         * User Search Logic - Find users by name or email
         * Excludes current admin for security and consistency
         * Partial matching for flexible user discovery
         */
        $users = User::where('id', '!=', Auth::id())
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")      // Search by name
                    ->orWhere('email', 'LIKE', "%{$query}%"); // Search by email
            })
            ->get();

        // HTML Generation for Search Results
        /**
         * Dynamic HTML Generation - Create table rows for search results
         * Maintains consistency with main user listing interface
         * Includes all action buttons for seamless user management
         */
        $html = '';
        if ($users->count() > 0) {
            // Generate HTML for each found user
            foreach ($users as $user) {
                $html .= '<tr>
                    <td class="px-4 py-2 border-b"><input type="checkbox" class="check-user" value="' . $user->getKey() . '"></td>
                    <td class="px-4 py-2 border-b">' . $user->getKey() . '</td>
                    <td class="px-4 py-2 border-b">' . e($user->name) . '</td>
                    <td class="px-4 py-2 border-b">' . e($user->email) . '</td>
                    <td class="px-4 py-2 border-b">' . e($user->role) . '</td>
                    <td class="px-4 py-2 border-b">
                        <div class="flex flex-wrap gap-2">
                            <a href="' . route('admin.user.edit', $user->getKey()) . '" class="px-3 py-1 bg-blue-500 text-white text-xs font-medium rounded hover:bg-blue-600 transition">' . __('admin.edit') . '</a>
                            <button type="button" class="px-3 py-1 bg-red-500 text-white text-xs font-medium rounded hover:bg-red-600 transition btn-delete" data-id="' . $user->getKey() . '">' . __('admin.delete') . '</button>
                            <a href="' . route('admin.user.show', $user->getKey()) . '" class="px-3 py-1 bg-green-500 text-white text-xs font-medium rounded hover:bg-green-600 transition">' . __('admin.view_details') . '</a>
                        </div>
                    </td>
                </tr>';
            }
        } else {
            // No results found message
            $html = '<tr><td colspan="6" class="text-center py-4 text-gray-500">No users found.</td></tr>';
        }

        return response()->json(['html' => $html]); // Return HTML for DOM insertion
    }
}
