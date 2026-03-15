<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Services\Export\WorkoutPlanPdfExporter;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class ExportController extends Controller
{
    /**
     * Export the user's workout plan as PDF.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function exportWorkoutPlanPdf(Request $request)
    {
        try {
            $user = $request->user()->load('profile');

            $exporter = new WorkoutPlanPdfExporter($user);

            // Check if user has any workout sessions
            if ($exporter->getSessions()->isEmpty()) {
                return ApiResponse::error(
                    __('api.no_plan_found'),
                    404
                );
            }

            // Generate HTML content
            $html = $exporter->generateHtml();

            // Check if DomPDF is available
            if (!class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
                // Return HTML version if DomPDF is not installed
                return response($html, 200)
                    ->header('Content-Type', 'text/html; charset=utf-8')
                    ->header('Content-Disposition', 'inline; filename="workout-plan.html"');
            }

            // Generate PDF using DomPDF
            $pdf = Pdf::loadHTML($html);
            $pdf->setPaper('A4', 'portrait');

            $fileName = 'dipodi-workout-plan-' . now()->format('Y-m-d') . '.pdf';

            // Return PDF download
            return $pdf->download($fileName);

        } catch (\Exception $e) {
            Log::error('PDF export failed: ' . $e->getMessage());

            return ApiResponse::serverError(__('api.export_failed'));
        }
    }

    /**
     * Export the user's workout plan as HTML (fallback when DomPDF is not available).
     */
    public function exportWorkoutPlanHtml(Request $request)
    {
        try {
            $user = $request->user()->load('profile');

            $exporter = new WorkoutPlanPdfExporter($user);

            // Check if user has any workout sessions
            if ($exporter->getSessions()->isEmpty()) {
                return ApiResponse::error(
                    __('api.no_plan_found'),
                    404
                );
            }

            // Generate HTML content
            $html = $exporter->generateHtml();

            return response($html, 200)
                ->header('Content-Type', 'text/html; charset=utf-8');

        } catch (\Exception $e) {
            Log::error('HTML export failed: ' . $e->getMessage());

            return ApiResponse::serverError(__('api.export_failed'));
        }
    }
}
