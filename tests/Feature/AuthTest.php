<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * AuthTest — Login, Logout, Redirect Guards
 * Run: php artisan test --filter AuthTest
 */
class AuthTest extends TestCase
{
    use RefreshDatabase;

    // ─── SETUP ────────────────────────────────────────────────────────────

    private function makeUser(array $attrs = []): User
    {
        $role = Role::factory()->create();
        return User::factory()->create(array_merge(['role_id' => $role->id], $attrs));
    }

    // ─── 1. GUEST REDIRECTS ───────────────────────────────────────────────

    public function test_guest_is_redirected_from_dashboard(): void
    {
        $this->get('/')->assertRedirect(route('login'));
    }

    public function test_login_page_is_accessible(): void
    {
        $this->get(route('login'))->assertOk();
    }

    // ─── 2. LOGIN ────────────────────────────────────────────────────────

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = $this->makeUser(['password' => bcrypt('secret123')]);

        $response = $this->post(route('login'), [
            'email'    => $user->email,
            'password' => 'secret123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_wrong_password(): void
    {
        $user = $this->makeUser(['password' => bcrypt('correct')]);

        $response = $this->post(route('login'), [
            'email'    => $user->email,
            'password' => 'wrong_password',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_login_requires_email_and_password(): void
    {
        $this->post(route('login'), [])
             ->assertSessionHasErrors(['email', 'password']);
    }

    // ─── 3. LOGOUT ───────────────────────────────────────────────────────

    public function test_authenticated_user_can_logout(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)
             ->post(route('logout'))
             ->assertRedirect('/');

        $this->assertGuest();
    }
}
