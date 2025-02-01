[🏠 Main](../README.md)

# 🧪 Testing Guide
## 📜 Overview
The project follows a structured testing approach with **unit, feature, and integration tests** to ensure stability and reliability.  
The backend is tested using `PHPUnit`.

## 🔨 Technologies
- `PHPUnit` – Laravel's default testing framework.
- `DatabaseTransactions` – Used instead of `RefreshDatabase` for faster test execution.
- `Laravel Horizon` – Queue monitoring and testing.
- `Laravel Reverb` – WebSockets and real-time event testing.

## 🏗 Running Backend Tests
### 🔹 Running All Tests
To execute all backend tests:
```
make test-back
```

To run a specific test file:
```
php artisan test --filter ExportControllerTest
```

## ✅ **Unit Tests (`tests/Unit/`)**
**Purpose:** Tests isolated logic such as services, policies, and helpers.

### Example Test Files:
- `tests/Unit/AuthControllerTest.php`
- `tests/Unit/ExportControllerTest.php`
- `tests/Unit/ExportPolicyTest.php`
- `tests/Unit/OrderControllerTest.php`
- `tests/Unit/ProductControllerTest.php`
- `tests/Unit/ProductTest.php`

## ✅ **Policy Tests**
**Purpose:** Ensures that only authorized users can perform specific actions.
### Example:
- `ExportPolicyTest.php`
 
## ✅ **Queue & WebSocket Tests**
**Purpose:** Ensures that Horizon queues and WebSocket events are triggered correctly.
### Example:
- `ExportControllerTest.php` – verifies that an export job dispatches `ExportOrdersJob`.

## 🏗 Backend Test Details
### 🔹 **Authentication Tests (`AuthControllerTest.php`)**
#### ✅ Tests:
- User **can register**.
- User **can log in**.
- Authenticated user **can retrieve profile data**.
- User **can log out**.

#### Example:
```php
public function test_user_can_login(): void
{
    $user = User::factory()->create(['password' => bcrypt('password')]);

    $response = $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure(['user', 'token']);
}
```

### 🔹 Export Tests (`ExportControllerTest.php`)
#### ✅ Tests:
- Admin **can fetch exports**.
- Non-admin **cannot fetch exports** (`403 Forbidden`).
- Admin **can start an export** (dispatches `ExportOrdersJob`).
- Admin **can download an export**.
- Non-admin **cannot start or download exports**.

#### Example:
```php
public function test_admin_can_start_export(): void
{
    Queue::fake();
    Event::fake();

    $admin = User::factory()->admin()->create();
    $token = $admin->createToken('test_token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/exports', ['format' => 'csv', 'select_all' => true]);

    $response->assertStatus(200)
        ->assertJson(['code' => 'EXPORT_RUNNING_SUCCESS']);

    Queue::assertPushed(ExportOrdersJob::class);
}
```

### 🔹 Export Policy Tests (`ExportPolicyTest.php`)
#### ✅ Tests:
- Admin **can view an export**.
- Non-admin **cannot view** an export.
- Admin **can create** an export.
- Non-admin **cannot create** an export.
- Admin **can download** an export.
- Non-admin **cannot download** an export.

#### Example:
```php
public function test_non_admin_cannot_download_export(): void
{
    $user = User::factory()->create(['is_admin' => false]);
    $export = Export::factory()->create(['user_id' => $user->id]);

    $this->assertFalse($user->can('download', $export));
}
```

### 🔹 Order Tests (OrderControllerTest.php)
#### ✅ Tests:
- Users **can view their own orders**.
- Users **cannot view other users’ orders**.
- Admin **can view all orders**.
- Users **can create an order**.
- Users **can update their own order status**.
- Users **cannot update other users’ orders**.
- Admin **can delete any order**.
- Users **cannot delete orders**.

#### Example:
```php
public function test_user_can_create_order(): void
{
    Event::fake();

    $user = User::factory()->create();
    $token = $user->createToken('test_token')->plainTextToken;

    $product = Product::factory()->create(['price' => 100]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/orders', [
            'products' => [['product_id' => $product->id, 'quantity' => 2]],
        ]);

    $response->assertStatus(201)
        ->assertJsonStructure(['data' => ['id', 'user_id', 'amount']]);

    $this->assertDatabaseHas('orders', ['user_id' => $user->id, 'amount' => 200]);
    Event::assertDispatched(OrderCreated::class);
}
```

### 🔹 Product Tests (`ProductControllerTest.php`, `ProductTest.php`)
#### ✅ Tests:
- Products **can be retrieved with pagination**.
- Returns an **empty list when no products exist**.
- Products **can be created**.
- Products **can be associated with orders**.

#### Example:
```php
public function test_can_fetch_paginated_products(): void
{
    Product::factory()->count(50)->create();

    $response = $this->getJson('/api/products');

    $response->assertStatus(200)
        ->assertJsonCount(48, 'data');
}
```

[⬅ Preview](backend.md) | [Next ➡](architecture.md)