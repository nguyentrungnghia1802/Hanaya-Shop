<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        // App uses email verification, so user is not immediately authenticated
        // Instead, they are redirected to verification notice
        $response->assertRedirect(route('verification.notice'));

        // Check that pending registration data is stored in session
        $this->assertNotNull(session('pending_registration'));
        $this->assertEquals('test@example.com', session('pending_registration.email'));
    }
}
