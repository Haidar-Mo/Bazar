<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Traits\ResponseTrait;
use Exception;

class ReportController extends Controller
{

    use ResponseTrait;

    public function index()
    {
        try {
            $reports = Report::with(['reportable'])->get();
            return $this->showResponse($reports, 'All reports retrieved successfully !!');
        } catch (Exception $e) {
            report($e);
            return $this->showError($e, 'An error occur while ');
        }
    }

    public function show(string $id)
    {
        try {
            $report = Report::with(['reportable'])->findOrFail($id);
            return $this->showResponse($report, 'Report retrieved successfully !!');
        } catch (Exception $e) {
            report($e);
            return $this->showError($e, 'An error occur while ');
        }
    }

    public function destroy(string $id)
    {
        try {
            $report = Report::findOrFail($id);
            $report->delete();
            return $this->showMessage('Report deleted successfully !!');
        } catch (Exception $e) {
            report($e);
            return $this->showError($e, 'An error occur while ');
        }
    }

    public function markAsRead(string $id)
    {
        try {
            $report = Report::findOrFail($id);
            $report->update(['is_read' => true]);
            return $this->showMessage('Report marked as read successfully !!');
        } catch (Exception $e) {
            report($e);
            return $this->showError($e, 'An error occur while marking the report as read');
        }
    }
}
