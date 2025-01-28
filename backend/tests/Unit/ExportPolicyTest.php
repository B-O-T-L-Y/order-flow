<?php

namespace Tests\Unit;

use App\Models\Export;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ExportPolicyTest extends TestCase
{
    use DatabaseTransactions;

    public function test_admin_can_view_export(): void
    {
        $admin = User::factory()->admin()->create();
        $export = Export::factory()->create(['user_id' => $admin->id]);

        $this->assertTrue($admin->can('view', $export));
    }

    public function test_non_admin_cannot_view_export(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $export = Export::factory()->create(['user_id' => $user->id]);

        $this->assertFalse($user->can('view', $export));
    }

    public function test_admin_can_create_export(): void
    {
        $admin = User::factory()->admin()->create();

        $this->assertTrue($admin->can('create', Export::class));
    }

    public function test_non_admin_cannot_create_export(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->assertFalse($user->can('create', Export::class));
    }

    public function test_admin_can_download_export(): void
    {
        $admin = User::factory()->admin()->create();
        $export = Export::factory()->create(['user_id' => $admin->id]);

        $this->assertTrue($admin->can('download', $export));
    }

    public function test_non_admin_cannot_download_export(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $export = Export::factory()->create(['user_id' => $user->id]);

        $this->assertFalse($user->can('download', $export));
    }
}
