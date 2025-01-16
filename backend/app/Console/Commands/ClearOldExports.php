<?php

namespace App\Console\Commands;

use App\Models\Export;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ClearOldExports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exports:clear {--days=30}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete old exports';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $days = (int)$this->option('days');

        Export::where('created_at', '<', now()->subDays($days))
            ->each(function (Export $export) {
                Storage::disk('exports')->delete($export->file_path);
                $export->delete();
            });

        $this->info("Old exports (older than {$days} days) have been deleted.");
    }
}
