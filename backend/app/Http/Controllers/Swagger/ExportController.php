<?php

namespace App\Http\Controllers\Swagger;

/**
 * @OA\Tag(
 *     name="Exports",
 *     description="Endpoints for exporting orders"
 * )
 *
 * @OA\Components(
 *      @OA\Schema(
 *          schema="Export",
 *          type="object",
 *          title="Export",
 *          description="Export model schema",
 *          @OA\Property(property="id", type="integer", example=1),
 *          @OA\Property(property="user_id", type="integer", example=10),
 *          @OA\Property(property="file_path", type="string", example="exports/orders_10_1612548795.csv"),
 *          @OA\Property(property="format", type="string", example="csv"),
 *          @OA\Property(property="status", type="string", example="completed"),
 *          @OA\Property(property="progress", type="integer", example=100),
 *          @OA\Property(property="total", type="integer", example=500),
 *          @OA\Property(property="created_at", type="string", format="date-time", example="2024-02-05T10:00:00Z"),
 *          @OA\Property(property="updated_at", type="string", format="date-time", example="2024-02-05T12:00:00Z")
 *      )
 *  )
 */
class ExportController
{
    /**
     * @OA\Get(
     *     path="/api/exports",
     *     summary="Get export history",
     *     description="Returns a list of exports for the authenticated user.",
     *     tags={"Exports"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of exports retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/Export")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function index()
    {
    }

    /**
     * @OA\Post(
     *     path="/api/exports",
     *     summary="Start an export",
     *     description="Starts the export process for orders.",
     *     tags={"Exports"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="format", type="string", example="csv", enum={"csv", "xlsx"}),
     *             @OA\Property(property="select_all", type="boolean", example=true),
     *             @OA\Property(property="selected_orders", type="array",
     *                 @OA\Items(type="integer", example=1)
     *             ),
     *             @OA\Property(property="excluded_orders", type="array",
     *                 @OA\Items(type="integer", example=2)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Export process started successfully"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function startExport()
    {
    }

    /**
     * Download an exported file.
     *
     * @OA\Get(
     *     path="/api/exports/download/{exportId}",
     *     summary="Download an export file",
     *     description="Download the exported file by its ID.",
     *     tags={"Exports"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="exportId",
     *         in="path",
     *         required=true,
     *         description="ID of the export file",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="File downloaded successfully"),
     *     @OA\Response(response=404, description="Export file not found"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function downloadExport()
    {
    }
}
