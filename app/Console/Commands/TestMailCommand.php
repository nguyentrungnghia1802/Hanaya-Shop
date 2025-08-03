<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class TestMailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:mail {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test mail configuration by sending a test email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        try {
            Mail::raw('This is a test email from Hanaya Shop. Mail configuration is working!', function($message) use ($email) {
                $message->to($email)
                        ->subject('Test Email from Hanaya Shop');
            });
            
            $this->info("Test email sent successfully to {$email}");
            return 0;
        } catch (\Exception $e) {
            $this->error("Failed to send test email: " . $e->getMessage());
            return 1;
        }
    }
}
