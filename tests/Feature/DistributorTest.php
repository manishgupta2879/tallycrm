<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Distributor;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * DistributorTest — Distributor CRUD + Guards
 * Run: php artisan test --filter DistributorTest
 */
class DistributorTest extends TestCase
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

    /** Reusable valid POST data for creating a distributor */
    private function validData(array $overrides = []): array
    {
        return array_merge([
            'code' => 'DT-AAA01',
            'name' => 'Test Distributor',
            'company_code' => 'CO-TEST01',
            'type' => 'Platinum',
            'address' => '123 Main St',
            'country' => '1',
            'region' => '1',
            'state' => '1',
            'city' => '1',
            'pincode' => '400001',
            'gst_number' => '27AAPFU0939F1ZV',
            'pan_number' => 'AAPFU0939F',
            'status' => 'Active',
            // Contacts (array fields)
            'contact_name' => ['John Doe'],
            'designation' => ['Manager'],
            'email' => ['john@example.com'],
            'mobile' => ['9876543210'],
            'location' => ['Mumbai'],
        ], $overrides);
    }

    // ─── 1. INDEX ─────────────────────────────────────────────────────────

    public function test_guest_cannot_access_distributors(): void
    {
        $this->get(route('distributors.index'))->assertRedirect(route('login'));
    }

    public function test_super_admin_can_view_distributors(): void
    {
        $this->actingAs($this->superAdmin())
            ->get(route('distributors.index'))
            ->assertOk();
    }

    // ─── 2. CREATE ────────────────────────────────────────────────────────

    public function test_super_admin_can_open_create_form(): void
    {
        $this->actingAs($this->superAdmin())
            ->get(route('distributors.create'))
            ->assertOk();
    }

    public function test_super_admin_can_create_distributor(): void
    {
        $admin = $this->superAdmin();

        $response = $this->actingAs($admin)->post(route('distributors.store'), $this->validData());

        $response->assertRedirect(route('distributors.index'));
        $this->assertDatabaseHas('distributors', ['code' => 'DT-AAA01']);
    }

    public function test_create_distributor_saves_contact(): void
    {
        $admin = $this->superAdmin();

        $this->actingAs($admin)->post(route('distributors.store'), $this->validData());

        $distributor = Distributor::where('code', 'DT-AAA01')->first();
        $this->assertNotNull($distributor);
        $this->assertDatabaseHas('contacts', ['name' => 'John Doe']);
    }

    public function test_create_distributor_requires_mandatory_fields(): void
    {
        $this->actingAs($this->superAdmin())
            ->post(route('distributors.store'), [])
            ->assertSessionHasErrors(['code', 'name', 'status']);
    }

    public function test_distributor_code_must_be_unique(): void
    {
        Distributor::factory()->create(['code' => 'DT-SAME']);

        $this->actingAs($this->superAdmin())
            ->post(route('distributors.store'), $this->validData(['code' => 'DT-SAME']))
            ->assertSessionHasErrors('code');
    }

    public function test_gst_number_must_be_valid_format(): void
    {
        $this->actingAs($this->superAdmin())
            ->post(route('distributors.store'), $this->validData(['gst_number' => 'INVALID-GST']))
            ->assertSessionHasErrors('gst_number');
    }

    public function test_pan_number_must_be_valid_format(): void
    {
        $this->actingAs($this->superAdmin())
            ->post(route('distributors.store'), $this->validData(['pan_number' => 'INVALIDPAN']))
            ->assertSessionHasErrors('pan_number');
    }

    // ─── 3. EDIT ──────────────────────────────────────────────────────────

    public function test_super_admin_can_view_edit_form(): void
    {
        $distributor = Distributor::factory()->create();

        $this->actingAs($this->superAdmin())
            ->get(route('distributors.edit', $distributor))
            ->assertOk();
    }

    // ─── 4. UPDATE ────────────────────────────────────────────────────────

    public function test_super_admin_can_update_distributor(): void
    {
        $admin = $this->superAdmin();
        $distributor = Distributor::factory()->create(['code' => 'DT-OLD01']);

        $response = $this->actingAs($admin)->put(
            route('distributors.update', $distributor),
            $this->validData(['code' => 'DT-OLD01', 'name' => 'Updated Name'])
        );

        $response->assertRedirect(route('distributors.index'));
        $this->assertDatabaseHas('distributors', ['id' => $distributor->id, 'name' => 'Updated Name']);
    }

    // ─── 5. DELETE ────────────────────────────────────────────────────────

    public function test_super_admin_can_delete_distributor(): void
    {
        $admin = $this->superAdmin();
        $distributor = Distributor::factory()->create();

        $this->actingAs($admin)
            ->delete(route('distributors.destroy', $distributor))
            ->assertRedirect(route('distributors.index'));

        $this->assertDatabaseMissing('distributors', ['id' => $distributor->id]);
    }

    // ─── 6. AUTHORIZATION ─────────────────────────────────────────────────

    public function test_regular_user_cannot_access_create_form(): void
    {
        $this->actingAs($this->regularUser())
            ->get(route('distributors.create'))
            ->assertForbidden();
    }

    public function test_regular_user_cannot_create_distributor(): void
    {
        $this->actingAs($this->regularUser())
            ->post(route('distributors.store'), $this->validData())
            ->assertForbidden();

        $this->assertDatabaseMissing('distributors', ['code' => 'DT-AAA01']);
    }

    public function test_regular_user_cannot_delete_distributor(): void
    {
        $distributor = Distributor::factory()->create();

        $this->actingAs($this->regularUser())
            ->delete(route('distributors.destroy', $distributor))
            ->assertForbidden();

        $this->assertDatabaseHas('distributors', ['id' => $distributor->id]);
    }
}
