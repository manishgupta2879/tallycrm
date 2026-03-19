<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * CompanyTest — Company CRUD + Guards
 * Run: php artisan test --filter CompanyTest
 */
class CompanyTest extends TestCase
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

    public function test_guest_cannot_access_companies(): void
    {
        $this->get(route('companies.index'))->assertRedirect(route('login'));
    }

    public function test_super_admin_can_view_companies(): void
    {
        $this->actingAs($this->superAdmin())
             ->get(route('companies.index'))
             ->assertOk();
    }

    // ─── 2. CREATE ────────────────────────────────────────────────────────

    public function test_super_admin_can_create_company(): void
    {
        $admin = $this->superAdmin();

        $response = $this->actingAs($admin)->post(route('companies.store'), [
            'pid'          => 'CO-TEST01',
            'name'         => 'Test Corp',
            'contact_name' => 'John Doe',
            'designation'  => 'Manager',
            'email'        => 'test@corp.com',
            'mobile'       => '9876543210',
            'territory'    => 'Maharashtra',
            'status'       => 'Active',
        ]);

        $response->assertRedirect(route('companies.index'));
        $this->assertDatabaseHas('companies', ['pid' => 'CO-TEST01', 'name' => 'Test Corp']);
    }

    public function test_create_company_requires_name_and_pid(): void
    {
        $this->actingAs($this->superAdmin())
             ->post(route('companies.store'), [])
             ->assertSessionHasErrors(['name', 'pid']);
    }

    public function test_company_pid_must_be_unique(): void
    {
        Company::factory()->create(['pid' => 'CO-EXIST']);

        $this->actingAs($this->superAdmin())
             ->post(route('companies.store'), [
                 'pid'    => 'CO-EXIST',
                 'name'   => 'Another Corp',
                 'status' => 'Active',
             ])
             ->assertSessionHasErrors('pid');
    }

    // ─── 3. EDIT ──────────────────────────────────────────────────────────

    public function test_super_admin_can_view_edit_form(): void
    {
        $company = Company::factory()->create();

        $this->actingAs($this->superAdmin())
             ->get(route('companies.edit', $company))
             ->assertOk();
    }

    // ─── 4. UPDATE ────────────────────────────────────────────────────────

    public function test_super_admin_can_update_company(): void
    {
        $admin   = $this->superAdmin();
        $company = Company::factory()->create();

        $response = $this->actingAs($admin)->put(route('companies.update', $company), [
            'pid'          => $company->pid,
            'name'         => 'Updated Corp Name',
            'contact_name' => $company->contact_name,
            'email'        => $company->email,
            'mobile'       => $company->mobile,
            'status'       => 'Active',
            'urls_loaded'  => '0',
        ]);

        $response->assertRedirect(route('companies.index'));
        $this->assertDatabaseHas('companies', ['id' => $company->id, 'name' => 'Updated Corp Name']);
    }

    // ─── 5. DELETE ────────────────────────────────────────────────────────

    public function test_super_admin_can_delete_company(): void
    {
        $admin   = $this->superAdmin();
        $company = Company::factory()->create();

        $this->actingAs($admin)
             ->delete(route('companies.destroy', $company))
             ->assertRedirect(route('companies.index'));

        $this->assertDatabaseMissing('companies', ['id' => $company->id]);
    }

    // ─── 6. AUTHORIZATION ─────────────────────────────────────────────────

    public function test_regular_user_cannot_create_company(): void
    {
        $this->actingAs($this->regularUser())
             ->post(route('companies.store'), ['name' => 'X', 'pid' => 'X'])
             ->assertForbidden();
    }

    public function test_regular_user_cannot_delete_company(): void
    {
        $company = Company::factory()->create();

        $this->actingAs($this->regularUser())
             ->delete(route('companies.destroy', $company))
             ->assertForbidden();

        $this->assertDatabaseHas('companies', ['id' => $company->id]);
    }
}
