<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Order\Order;
use App\Notifications\NewOrderPending;

class TestEmailConfigCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:email-config';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email configuration by sending a sample notification';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing email configuration...');
        
        // Show current APP_URL
        $this->info('Current APP_URL: ' . config('app.url'));
        
        // Show mail configuration
        $this->info('Mail configuration:');
        $this->info('- MAIL_MAILER: ' . config('mail.default'));
        $this->info('- MAIL_HOST: ' . config('mail.mailers.smtp.host'));
        $this->info('- MAIL_FROM_ADDRESS: ' . config('mail.from.address'));
        $this->info('- MAIL_FROM_NAME: ' . config('mail.from.name'));
        
        // Test URL generation
        $testUrl = config('app.url') . '/admin/orders/1';
        $this->info('Generated URL for admin orders: ' . $testUrl);
        
        // Test if we can find any admin users
        $adminUsers = User::where('role', 'admin')->get();
        $this->info('Found ' . $adminUsers->count() . ' admin users');
        
        // Test if we can find any orders
        $orders = Order::limit(1)->get();
        if ($orders->count() > 0) {
            $this->info('Found sample order with ID: ' . $orders->first()->id);
        } else {
            $this->warn('No orders found in database');
        }
        
        return 0;
    }
}
