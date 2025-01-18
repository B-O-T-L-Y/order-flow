<?php

namespace App\Services;

use App\Events\ExportCompleted;
use App\Events\ExportFailed;
use App\Events\ExportProgress;
use App\Models\Export;
use App\Models\Order;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Illuminate\Support\Facades\Storage;

class OrderExportService
{
    public function generateExport(int $exportId, array $selectedOrders): void
    {
        $export = Export::findOrFail($exportId);

        $export->update([
            'status' => 'in_progress',
            'progress' => 0,
            'total' => 0,
        ]);

        try {
            $writer = $export->format === 'xlsx'
                ? WriterEntityFactory::createXLSXWriter()
                : WriterEntityFactory::createCSVWriter();

            $fileName = "orders_{$export->user_id}_" . time() . ".{$export->format}";
            $fullPath = Storage::disk('exports')->path($fileName);

            $writer->openToFile($fullPath);

            $writer->addRow(WriterEntityFactory::createRowFromArray([
                'ID', 'User ID', 'Status', 'Amount', 'Created At', 'Updated At', 'Products',
            ]));

            $query = Order::whereIn('id', $selectedOrders)->with(['products']);

            $total = $query->count();

            $export->update(['total' => $total]);

            $processed = 0;

            $query->chunk(1000, function ($orders) use ($writer, $total, &$processed, $export) {
                foreach ($orders as $order) {
                    $writer->addRow(WriterEntityFactory::createRowFromArray([
                        $order->id,
                        $order->user_id,
                        $order->status,
                        $order->amount,
                        $order->created_at,
                        $order->updated_at,
                        $order->products->pluck('name')->implode(', '),
                    ]));
                }

                $processed += $orders->count();

                $export->update(['progress' => $processed]);

                broadcast(new ExportProgress($export, $processed, $total));
            });

            $writer->close();

            $export->update([
                'status' => 'completed',
                'file_path' => $fileName,
                'progress' => $total,
            ]);

            broadcast(new ExportCompleted($export));
        } catch (\Throwable $e) {
            \Log::error("Export #{$exportId} failed: ".$e->getMessage());

            $export->update(['status' => 'failed']);

            broadcast(new ExportFailed($export));
        }
    }
}
