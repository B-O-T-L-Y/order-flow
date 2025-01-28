<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExportRequest;
use App\Jobs\ExportOrdersJob;
use App\Models\Export;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): JsonResponse
    {
        $exports = Export::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['data' => $exports]);
    }

    /**
     * @throws AuthorizationException
     */
    public function startExport(ExportRequest $request, OrderService $orderService): JsonResponse
    {
        $this->authorize('create', Export::class);

        $user = $request->user();
        $format = $request->input('format', 'csv');
        $selectAll = $request->boolean('select_all');
        $selectedOrders = $request->input('selected_orders', []);
        $excludesOrders = $request->input('excluded_orders', []);

        $export = Export::create([
            'user_id' => $user->id,
            'file_path' => '',
            'format' => $format,
            'status' => 'pending'
        ]);

        if ($selectAll) {
            $filters = $request->only(['user_id', 'status', 'start_date', 'end_date', 'min_amount', 'max_amount']);

            /** @var Order $query */
            $query = $orderService->getAllFilteredOrdersQuery($filters, $user->id, $user->is_admin);

            if (!empty($excludesOrders)) {
                $query->whereNotIn('id', $excludesOrders);
            }

            $finalIds = $query->pluck('id')->toArray();
        } else {
            $finalIds = $selectedOrders;
        }


        dispatch(new ExportOrdersJob($export->id, $finalIds));

        return response()->json([
            'message' => 'Export running successfully',
            'export_id' => $export->id,
            'code' => 'EXPORT_RUNNING_SUCCESS',
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function downloadExport(int $exportId): StreamedResponse
    {
        $export = Export::where('id', $exportId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $this->authorize('download', $export);

        if (!Storage::disk('exports')->exists($export->file_path)) {
            abort(404, 'Export file not found.');
        }

        return Storage::disk('exports')->download($export->file_path);
    }
}
