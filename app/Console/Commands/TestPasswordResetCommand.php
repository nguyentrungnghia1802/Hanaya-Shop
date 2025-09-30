<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\ResetPassword;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TestPasswordResetCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:password-reset {email} {--locale=en}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test password reset functionality with email address';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $locale = $this->option('locale');

        // Tìm user với email
        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error("User with email {$email} not found!");

            return 1;
        }

        $this->info("Found user: {$user->name} ({$user->email})");

        // Tạo token reset
        $token = Str::random(60);

        // Lưu token vào database
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => hash('sha256', $token),
                'created_at' => now(),
            ]
        );

        $this->info("Generated reset token for locale: {$locale}");

        // Test notification với locale
        try {
            app()->setLocale($locale);
            $user->notify(new ResetPassword($token, $locale));

            $this->info('✅ Password reset email sent successfully!');
            $this->info("📧 Email sent to: {$user->email}");
            $this->info("🌐 Locale: {$locale}");

            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Failed to send email: '.$e->getMessage());

            return 1;
        }
    }
}
