<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order\Order;
use App\Models\User;
use App\Notifications\CustomerNewOrderPending;
// Import all notifications
use App\Notifications\CustomerOrderCancelledNotification;
use App\Notifications\CustomerOrderCompletedNotification;
use App\Notifications\CustomerOrderConfirmedNotification;
use App\Notifications\CustomerOrderShippedNotification;
use App\Notifications\NewOrderPending;
use App\Notifications\OrderCancelledNotification;
use App\Notifications\OrderCompletedNotification;
use App\Notifications\OrderConfirmedNotification;
use App\Notifications\OrderPaidNotification;
use App\Notifications\OrderShippedNotification;
use Illuminate\Support\Facades\Session;

class NotificationTestController extends Controller
{
    /**
     * Test sending all admin and customer notifications.
     * Admin notifications are sent immediately in English.
     * Customer notifications are queued and sent in the current locale.
     * Returns a JSON response with the test results.
     */
    public function test()
    {
        $admin = User::where('role', 'admin')->first();
        $customer = User::where('role', 'user')->first();
        $order = Order::first();

        if (! $admin || ! $customer || ! $order) {
            return response()->json(['error' => 'Missing test data'], 400);
        }

        $results = [];
        $currentLocale = Session::get('locale', config('app.locale'));

        try {
            // Test Admin Notifications (Immediate - English only)
            $results['admin_notifications'] = [
                'new_order' => $this->testNotification($admin, NewOrderPending::class, [$order]),
                'confirmed' => $this->testNotification($admin, OrderConfirmedNotification::class, [$order]),
                'shipped' => $this->testNotification($admin, OrderShippedNotification::class, [$order]),
                'completed' => $this->testNotification($admin, OrderCompletedNotification::class, [$order]),
                'paid' => $this->testNotification($admin, OrderPaidNotification::class, [$order]),
                'cancelled' => $this->testNotification($admin, OrderCancelledNotification::class, [$order]),
            ];

            // Test Customer Notifications (Queued - Multilingual)
            $results['customer_notifications'] = [
                'new_order' => $this->testNotification($customer, CustomerNewOrderPending::class, [$order, $currentLocale]),
                'confirmed' => $this->testNotification($customer, CustomerOrderConfirmedNotification::class, [$order, $currentLocale]),
                'shipped' => $this->testNotification($customer, CustomerOrderShippedNotification::class, [$order, $currentLocale]),
                'completed' => $this->testNotification($customer, CustomerOrderCompletedNotification::class, [$order, $currentLocale]),
                'cancelled' => $this->testNotification($customer, CustomerOrderCancelledNotification::class, [$order, $currentLocale]),
            ];

            return response()->json([
                'success' => true,
                'message' => 'All notifications tested successfully',
                'results' => $results,
                'admin_email' => $admin->email,
                'customer_email' => $customer->email,
                'locale' => $currentLocale,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Test failed: '.$e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ], 500);
        }
    }

    /**
     * Helper function to send a notification to a user and return the result.
     * Instantiates the notification class with given parameters and calls notify().
     * Returns 'SUCCESS' or error message string.
     *
     * @param  User  $user  The user to notify
     * @param  string  $notificationClass  The notification class name
     * @param  array  $params  Parameters to pass to the notification constructor
     * @return string
     */
    private function testNotification($user, $notificationClass, $params)
    {
        try {
            $notification = new $notificationClass(...$params);
            $user->notify($notification);

            return 'SUCCESS';
        } catch (\Exception $e) {
            return 'FAILED: '.$e->getMessage();
        }
    }
}
