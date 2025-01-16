<?php

namespace App\Services;

use App\Events\ExportCompleted;
use App\Models\Export;
use App\Models\Order;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Illuminate\Support\Facades\Storage;

class OrderExportService
{
    public function generateExport(int $exportId, array $selectedOrders): void
    {
        $export = Export::findOrFail($exportId);

        $fileName = "orders_{$export->user_id}_" . time() . ".{$export->format}";

        $writer = $export->format === 'xlsx'
            ? WriterEntityFactory::createXLSXWriter()
            : WriterEntityFactory::createCSVWriter();

        $fullPath = Storage::disk('exports')->path($fileName);

        $writer->openToFile($fullPath);

        $writer->addRow(WriterEntityFactory::createRowFromArray([
            'ID', 'User ID', 'Status', 'Amount', 'Created At', 'Updated At', 'Products',
        ]));

        $query = Order::whereIn('id', $selectedOrders)->with(['products']);

        $query->chunk(1000, function ($orders) use ($writer) {
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
        });

        $writer->close();

        $export->update([
            'file_path' => $fileName,
            'status' => 'completed',
        ]);

        broadcast(new ExportCompleted($export));
    }
}
