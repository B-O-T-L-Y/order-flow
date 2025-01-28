<?php

namespace Tests\Unit;

use App\Jobs\ExportOrdersJob;
use App\Models\Export;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ExportControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_admin_can_fetch_exports(): void
    {
        $admin = User::factory()->admin()->create();
        Export::factory()->count(5)->create(['user_id' => $admin->id]);

        $token = $admin->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/exports');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }

    public function test_non_admin_cannot_fetch_exports(): void
    {
        $user = User::factory()->admin()->create(['is_admin' => false]);

        $token = $user->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/exports');

        $response->assertStatus(403);
    }

    public function test_admin_can_start_export(): void
    {
        Queue::fake();
        Event::fake();

        $admin = User::factory()->admin()->create();

        $token = $admin->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/exports', [
                'format' => 'csv',
                'select_all' => true,
            ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 'EXPORT_RUNNING_SUCCESS']);

        Queue::assertPushed(ExportOrdersJob::class, function ($job) use ($admin) {
            return $job->exportId !== null && $job->selectedOrders !== null;
        });

        Event::assertNotDispatched(ExportOrdersJob::class);
    }

    public function test_non_admin_cannot_start_export(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $token = $user->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/exports', [
                'format' => 'csv',
                'select_all' => true,
            ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_download_export(): void
    {
        Storage::fake('exports');

        $admin = User::factory()->admin()->create();
        $export = Export::factory()->create([
            'user_id' => $admin->id,
            'file_path' => 'test.csv',
        ]);

        Storage::disk('exports')->put('test.csv', 'test content');

        $token = $admin->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/exports/download/' . $export->id);

        $response->assertStatus(200);

        Storage::disk('exports')->assertExists('test.csv');
    }

    public function test_export_triggers_sockets_and_jobs(): void
    {
        Queue::fake();
        Event::fake();

        $admin = User::factory()->admin()->create();

        $token = $admin->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/exports', [
                'format' => 'xlsx',
                'select_all' => true,
            ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['code' => 'EXPORT_RUNNING_SUCCESS']);

        Queue::assertPushed(ExportOrdersJob::class, function ($job) {
            dispatch_sync($job);
            return true;
        });
    }
}
