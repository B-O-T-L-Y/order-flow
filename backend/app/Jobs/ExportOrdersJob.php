<?php

namespace App\Jobs;

use App\Services\OrderExportService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExportOrdersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $exportId,
        public array $selectedOrders,
    )
    {
    }

    /**
     * Execute the job.
     */
    public function handle(OrderExportService $service): void
    {
        try {
            $service->generateExport($this->exportId, $this->selectedOrders);
        } catch (\Exception $exception) {
            \Log::error('Export error: '.$exception->getMessage());
        }
    }
}
