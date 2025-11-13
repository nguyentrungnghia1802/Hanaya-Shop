<?php

/**
 * Address Controller
 *
 * This controller handles address management functionality for user shipping addresses
 * in the Hanaya Shop e-commerce application. It provides CRUD operations for customer
 * addresses used during checkout and shipping processes.
 *
 * Key Features:
 * - Address creation and validation
 * - User address association and security
 * - JSON API responses for AJAX integration
 * - Error handling and exception management
 * - Input validation for data integrity
 *
 * @author Hanaya Shop Development Team
 *
 * @version 1.0
 */

namespace App\Http\Controllers;

use App\Models\Address;        // HTTP request handling
use App\Models\User;             // Address model for database operations
use Illuminate\Http\Request; // User authentication services
use Illuminate\Support\Facades\Auth;                // User model (imported but not directly used)

/**
 * Address Controller Class
 *
 * Manages customer shipping addresses for the e-commerce platform.
 * Handles creation, validation, and storage of user addresses with
 * proper authentication and error handling.
 */
class AddressController extends Controller
{
    /**
     * Store a New Address
     *
     * Creates a new shipping address for the authenticated user.
     * This method handles AJAX requests from the checkout process,
     * allowing users to add addresses dynamically without page refresh.
     *
     * Validation Rules:
     * - phone_number: Required field for delivery contact
     * - address: Required field for shipping location
     *
     * Security Features:
     * - Automatically associates address with authenticated user
     * - Prevents users from creating addresses for other users
     * - Returns detailed error information for debugging
     *
     * @param  \Illuminate\Http\Request  $request  HTTP request with address data
     * @return \Illuminate\Http\JsonResponse JSON response with success or error status
     */
    public function store(Request $request)
    {
        try {
            // Input Validation
            /**
             * Address Data Validation - Ensures required fields are provided
             * phone_number: Essential for delivery contact and communication
             * address: Complete shipping address for accurate delivery
             */
            $validated = $request->validate([
                'phone_number' => 'required',  // Required phone number for delivery contact
                'address' => 'required',       // Required complete address for shipping
            ]);

            // Address Creation
            /**
             * Create New Address Record - Store validated address in database
             * User ID is automatically set from authenticated user for security
             * Prevents unauthorized address creation for other users
             */
            $address = Address::create([
                'user_id' => Auth::id(),                        // Associate with current authenticated user
                'phone_number' => $validated['phone_number'],   // Validated phone number
                'address' => $validated['address'],             // Validated address string
            ]);

            // Success Response
            /**
             * Success Response - Return address data for immediate use
             * Status indicates successful creation for frontend handling
             * Address object contains full record including auto-generated ID
             */
            return response()->json([
                'status' => 'success',     // Success indicator for frontend
                'address' => $address,     // Complete address record with ID
            ]);

        } catch (\Throwable $e) {
            // Error Handling
            /**
             * Exception Handling - Catch and handle all types of errors
             * Provides detailed error information for debugging purposes
             * Returns structured error response for consistent API behavior
             */
            return response()->json([
                'status' => 'error',               // Error indicator for frontend
                'message' => $e->getMessage(),     // Exception message for debugging
                'line' => $e->getLine(),           // Line number where error occurred
                'file' => $e->getFile(),           // File path where error occurred
            ], 500); // HTTP 500 Internal Server Error status
        }
    }
}
