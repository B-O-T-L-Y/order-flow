<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExportRequest;
use App\Jobs\ExportOrdersJob;
use App\Models\Export;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $exports = Export::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['data' => $exports]);
    }

    public function startExport(ExportRequest $request): JsonResponse
    {
        $user = $request->user();
        $format = $request->input('format', 'csv');
        $selectedOrders = $request->input('selected_orders', []);

        $export = Export::create([
            'user_id' => $user->id,
            'file_path' => '',
            'format' => $format,
            'status' => 'pending'
        ]);

        dispatch(new ExportOrdersJob($export->id, $selectedOrders));

        return response()->json([
            'message' => 'Export running successfully',
            'export_id' => $export->id,
            'code' => 'EXPORT_RUNNING_SUCCESS',
        ]);
    }

    public function downloadExport(int $exportId): StreamedResponse
    {
        $export = Export::where('id', $exportId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if (!Storage::disk('exports')->exists($export->file_path)) {
            abort(404, 'Export file not found.');
        }

        return Storage::disk('exports')->download($export->file_path);
    }
}
