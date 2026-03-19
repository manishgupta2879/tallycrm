<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * RoleTest — Role CRUD + Permission Guards
 * Run: php artisan test --filter RoleTest
 */
class RoleTest extends TestCase
{
    use RefreshDatabase;

    // ─── SETUP ────────────────────────────────────────────────────────────

    private function superAdmin(): User
    {
        $role = Role::factory()->create();
        return User::factory()->create(['is_super_admin' => true, 'role_id' => $role->id]);
    }

    private function regularUser(): User
    {
        $role = Role::factory()->create();
        return User::factory()->create(['is_super_admin' => false, 'role_id' => $role->id]);
    }

    // ─── 1. INDEX ─────────────────────────────────────────────────────────

    public function test_guest_cannot_access_roles(): void
    {
        $this->get(route('roles.index'))->assertRedirect(route('login'));
    }

    public function test_super_admin_can_view_roles(): void
    {
        $this->actingAs($this->superAdmin())
             ->get(route('roles.index'))
             ->assertOk();
    }

    // ─── 2. CREATE ────────────────────────────────────────────────────────

    public function test_super_admin_can_create_role(): void
    {
        $admin = $this->superAdmin();

        $response = $this->actingAs($admin)->post(route('roles.store'), [
            'name' => 'Manager',
            'slug' => 'manager',
        ]);

        $response->assertRedirect(route('roles.index'));
        $this->assertDatabaseHas('roles', ['slug' => 'manager']);
    }

    public function test_create_role_requires_name_and_slug(): void
    {
        $this->actingAs($this->superAdmin())
             ->post(route('roles.store'), [])
             ->assertSessionHasErrors(['name', 'slug']);
    }

    public function test_role_slug_must_be_unique(): void
    {
        Role::factory()->create(['slug' => 'manager']);

        $this->actingAs($this->superAdmin())
             ->post(route('roles.store'), ['name' => 'Manager 2', 'slug' => 'manager'])
             ->assertSessionHasErrors('slug');
    }

    // ─── 3. UPDATE ────────────────────────────────────────────────────────

    public function test_super_admin_can_update_role(): void
    {
        $admin = $this->superAdmin();
        $role  = Role::factory()->create(['name' => 'Old Name', 'slug' => 'old-name']);

        $response = $this->actingAs($admin)->put(route('roles.update', $role), [
            'name' => 'New Name',
            'slug' => 'new-name',
        ]);

        $response->assertRedirect(route('roles.index'));
        $this->assertDatabaseHas('roles', ['id' => $role->id, 'name' => 'New Name']);
    }

    // ─── 4. DELETE ────────────────────────────────────────────────────────

    public function test_super_admin_can_delete_role_with_no_users(): void
    {
        $admin = $this->superAdmin();
        $role  = Role::factory()->create();

        $this->actingAs($admin)
             ->delete(route('roles.destroy', $role))
             ->assertRedirect(route('roles.index'));

        $this->assertDatabaseMissing('roles', ['id' => $role->id]);
    }

    public function test_cannot_delete_role_assigned_to_users(): void
    {
        $admin  = $this->superAdmin();
        $role   = Role::factory()->create();
        User::factory()->create(['role_id' => $role->id, 'is_super_admin' => false]);

        $response = $this->actingAs($admin)->delete(route('roles.destroy', $role));

        $response->assertRedirect(route('roles.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('roles', ['id' => $role->id]); // Still exists
    }

    // ─── 5. AUTHORIZATION ─────────────────────────────────────────────────

    public function test_regular_user_cannot_create_role(): void
    {
        $this->actingAs($this->regularUser())
             ->post(route('roles.store'), ['name' => 'X', 'slug' => 'x'])
             ->assertForbidden();
    }

    public function test_regular_user_cannot_delete_role(): void
    {
        $role = Role::factory()->create();

        $this->actingAs($this->regularUser())
             ->delete(route('roles.destroy', $role))
             ->assertForbidden();

        $this->assertDatabaseHas('roles', ['id' => $role->id]);
    }
}
