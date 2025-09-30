<?php

/**
 * Notification Management Controller
 *
 * This controller handles notification management functionality in the Hanaya Shop
 * e-commerce application. It provides user notification management including
 * marking notifications as read and managing notification states.
 *
 * Key Features:
 * - Mark individual notifications as read
 * - User authentication validation
 * - Notification ownership verification
 * - JSON API responses for AJAX integration
 * - Comprehensive error handling and status codes
 *
 * Security Features:
 * - User authentication verification
 * - Notification ownership validation
 * - Proper HTTP status code responses
 * - Unauthorized access prevention
 *
 * Use Cases:
 * - Order status update notifications
 * - System announcements
 * - Promotional notifications
 * - Real-time notification management
 *
 * @author Hanaya Shop Development Team
 *
 * @version 1.0
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request; // HTTP request handling

/**
 * Notification Controller Class
 *
 * Manages user notification interactions including reading status updates
 * and notification state management. Provides secure notification handling
 * with proper authentication and ownership validation.
 */
class NotificationController extends Controller
{
    /**
     * Mark Notification as Read
     *
     * Marks a specific notification as read for the authenticated user.
     * This method handles AJAX requests for real-time notification management,
     * allowing users to mark notifications as read without page refresh.
     *
     * Security Process:
     * - Validates user authentication
     * - Verifies notification ownership
     * - Prevents unauthorized notification access
     * - Returns appropriate error codes for different failure scenarios
     *
     * API Response:
     * - Success: JSON with 'ok' status
     * - Authentication failure: HTTP 401 with error message
     * - Not found: HTTP 404 with error message
     *
     * @param  \Illuminate\Http\Request  $request  HTTP request with notification ID
     * @return \Illuminate\Http\JsonResponse JSON response with status or error
     */
    public function markAsRead(Request $request)
    {
        // Extract Notification ID
        /**
         * Notification Identification - Get notification ID from request
         * ID parameter identifies which notification to mark as read
         * Used for database lookup and ownership validation
         */
        $notificationId = $request->input('id'); // Notification ID to mark as read

        // User Authentication Check
        /**
         * Authentication Validation - Ensure user is logged in
         * Notifications are user-specific and require authentication
         * Prevents unauthorized notification access and modification
         */
        $user = \Illuminate\Support\Facades\Auth::user(); // Get currently authenticated user

        // Authentication Error Response
        /**
         * Authentication Failure Handling - Handle unauthenticated requests
         * Returns HTTP 401 Unauthorized status with error message
         * Prevents notification access for non-authenticated users
         */
        if (! $user) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }

        // Notification Lookup and Ownership Validation
        /**
         * Notification Ownership Validation - Find notification owned by user
         * Searches only unread notifications belonging to authenticated user
         * Prevents users from accessing notifications of other users
         * Returns null if notification not found or not owned by user
         */
        $notification = $user->notifications->whereNull('read_at')->where('id', $notificationId)->first();

        // Notification Not Found Error Response
        /**
         * Not Found Handling - Handle invalid or inaccessible notifications
         * Returns HTTP 404 Not Found status with error message
         * Occurs when notification ID is invalid or belongs to another user
         */
        if (! $notification) {
            return response()->json(['error' => 'Notification not found'], 404);
        }

        // Mark Notification as Read
        /**
         * Read Status Update - Change notification status to read
         * Uses Laravel's built-in markAsRead() method
         * Updates the read_at timestamp in the database
         * Changes notification from unread to read state
         */
        $notification->markAsRead();

        // Success Response
        /**
         * Success Confirmation - Return success status to frontend
         * Indicates successful notification status update
         * Allows frontend to update UI and notification counters
         */
        return response()->json(['status' => 'ok']);
    }
}
