# Laravel Testing — Quick Reference Guide

---

## Step 1 — Run Tests

```bash
php artisan test                         # Run all tests
php artisan test --filter UserTest       # Run one file
php artisan test --filter test_guest_cannot_access_user_index  # Run one test
```

---

## Step 2 — Types of Tests

| Type | Folder | Tests What |
|---|---|---|
| **Feature** | `tests/Feature/` | Full HTTP flow, DB, Auth |
| **Unit** | `tests/Unit/` | Single function/class |

> Beginners: Always start with **Feature Tests**.

---

## Step 3 — Standard Structure

```php
<?php
namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SomethingTest extends TestCase
{
    use RefreshDatabase; // Clean DB before each test

    public function test_something_happens(): void
    {
        // 1. ARRANGE — create data
        // 2. ACT     — make request
        // 3. ASSERT  — check result
    }
}
```

---

## Step 4 — Naming Rules

| ✅ Correct | ❌ Wrong |
|---|---|
| `test_guest_cannot_view_users` | `testGuest` |
| `test_admin_can_create_distributor` | `test1` |
| `test_email_field_is_required` | `it_works` |

> **Pattern**: `test_{who}_{can/cannot}_{action}_{thing}`

---

## Step 5 — Built-in Assert Methods

### HTTP / Response
| Method | Checks |
|---|---|
| `assertStatus(200)` | Response code |
| `assertOk()` | Code is 200 |
| `assertForbidden()` | Code is 403 |
| `assertNotFound()` | Code is 404 |
| `assertRedirect(route('x'))` | Redirected to route |
| `assertSee('text')` | Text on page |
| `assertDontSee('text')` | Text NOT on page |
| `assertJson([...])` | JSON response matches |
| `assertSessionHas('success')` | Flash message exists |
| `assertSessionHasErrors(['email'])` | Validation failed for field |

### Database
| Method | Checks |
|---|---|
| `assertDatabaseHas('table', [...])` | Row exists |
| `assertDatabaseMissing('table', [...])` | Row does NOT exist |
| `assertDatabaseCount('table', 5)` | Table has N rows |
| `assertSoftDeleted('table', [...])` | Record is soft deleted |

---

## Step 6 — Common Helpers

### Auth
```php
$this->actingAs($user)               // Act as logged-in user
$this->actingAs($user)->get('/page') // Request as that user
```

### HTTP Methods
```php
$this->get(route('users.index'))
$this->post(route('users.store'), $data)
$this->put(route('users.update', $model), $data)
$this->delete(route('users.destroy', $model))
```

### Factories
```php
User::factory()->create()                  // 1 record saved to DB
User::factory()->create(['name' => 'X'])   // With specific value
User::factory(5)->create()                 // Save 5 records
User::factory()->make()                    // Build without saving
```

### RefreshDatabase
```php
use RefreshDatabase; // Rolls back DB after every test = clean slate
```

---

## Step 7 — Test Files in This Project

| File | Module |
|---|---|
| `tests/Feature/AuthTest.php` | Login / Logout |
| `tests/Feature/UserTest.php` | User CRUD + Guards |
| `tests/Feature/RoleTest.php` | Role CRUD |
| `tests/Feature/CompanyTest.php` | Company CRUD |
| `tests/Feature/DistributorTest.php` | Distributor CRUD |

---

## Step 8 — Quick Commands

```bash
php artisan test                          # Run all
php artisan test --filter UserTest        # One file
php artisan make:test SomethingTest       # New test file
php artisan make:factory ModelFactory --model=Model  # New factory
```
