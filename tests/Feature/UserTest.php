<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * UserTest
 * Tests: List, Create, Update, Delete Users & Auth Guard.
 *
 * Run: php artisan test --filter UserTest
 */
class UserTest extends TestCase
{
    use RefreshDatabase; // Resets DB after every test

    // ─── SETUP ─────────────────────────────────────────────────────────────

    /**
     * Create a Super Admin user to bypass all Gate checks.
     */
    private function superAdmin(): User
    {
        $role = Role::factory()->create(['name' => 'Admin', 'slug' => 'admin']);

        return User::factory()->create([
            'is_super_admin' => true,
            'role_id' => $role->id,
        ]);
    }

    /**
     * Create a regular user with no special permissions.
     */
    private function regularUser(): User
    {
        $role = Role::factory()->create(['name' => 'Viewer', 'slug' => 'viewer']);

        return User::factory()->create([
            'is_super_admin' => false,
            'role_id' => $role->id,
        ]);
    }

    // ─── 1. AUTHENTICATION GUARD ────────────────────────────────────────────

    /** Guest cannot access the user list */
    public function test_guest_cannot_access_user_index(): void
    {
        $response = $this->get(route('users.index'));

        $response->assertRedirect(route('login')); // Redirected to login
    }

    /** Logged-in user can access the user list */
    public function test_authenticated_user_can_access_user_index(): void
    {
        $admin = $this->superAdmin();

        $response = $this->actingAs($admin)->get(route('users.index'));

        $response->assertStatus(200);
    }

    // ─── 2. CREATE USER ─────────────────────────────────────────────────────

    /** Super admin can create a user */
    public function test_super_admin_can_create_user(): void
    {
        $admin = $this->superAdmin();
        $role = Role::factory()->create();

        $response = $this->actingAs($admin)->post(route('users.store'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role_id' => $role->id,
            'status' => '1',
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    /** Cannot create a user with a duplicate email */
    public function test_cannot_create_user_with_duplicate_email(): void
    {
        $admin = $this->superAdmin();
        $existingUser = User::factory()->create(['email' => 'exists@example.com']);
        $role = Role::factory()->create();

        $response = $this->actingAs($admin)->post(route('users.store'), [
            'name' => 'Duplicate',
            'email' => 'exists@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role_id' => $role->id,
        ]);

        $response->assertSessionHasErrors('email'); // Validation error
    }


    /** Cannot create a user without required fields */
    public function test_create_user_requires_name_email_password(): void
    {
        $admin = $this->superAdmin();

        $response = $this->actingAs($admin)->post(route('users.store'), []);

        $response->assertSessionHasErrors(['name', 'email', 'password']);
    }

    // ─── 3. UPDATE USER ─────────────────────────────────────────────────────

    /** Super Admin can update a user */
    public function test_super_admin_can_update_user(): void
    {
        $admin = $this->superAdmin();
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->put(route('users.update', $user), [
            'name' => 'Updated Name',
            'email' => $user->email,
            'role_id' => $user->role_id,
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Updated Name']);
    }

    // ─── 4. DELETE USER ─────────────────────────────────────────────────────

    /** Super Admin can delete a user */
    public function test_super_admin_can_delete_user(): void
    {
        $admin = $this->superAdmin();
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->delete(route('users.destroy', $user));

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    /** User cannot delete their own account */
    public function test_user_cannot_delete_own_account(): void
    {
        $admin = $this->superAdmin();

        $response = $this->actingAs($admin)->delete(route('users.destroy', $admin));

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('error'); // Should show error message
        $this->assertDatabaseHas('users', ['id' => $admin->id]); // Still exists
    }

    // ─── 5. AUTHORIZATION ───────────────────────────────────────────────────

    /** Regular user cannot access create user form */
    public function test_regular_user_cannot_access_create_user_form(): void
    {
        $viewer = $this->regularUser();

        $response = $this->actingAs($viewer)->get(route('users.create'));

        $response->assertStatus(403); // Forbidden
    }

    /** Regular user cannot delete other users */
    public function test_regular_user_cannot_delete_user(): void
    {
        $viewer = $this->regularUser();
        $another = User::factory()->create();

        $response = $this->actingAs($viewer)->delete(route('users.destroy', $another));

        $response->assertStatus(403);
        $this->assertDatabaseHas('users', ['id' => $another->id]); // Not deleted
    }
}
