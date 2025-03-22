<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class ReportController extends Controller
{

    use ResponseTrait;

    public function index()
    {
        $reports = Report::with(['reportable'])->get();
        return $this->showResponse($reports, 'All reports retrieved successfully !!');
    }

    public function show(string $id)
    {
        $report = Report::with(['reportable'])->findOrFail($id);
        $report->update(['status' => 'reviewed']);
        return $this->showResponse($report, 'Report retrieved successfully !!');
    }

    public function destroy(string $id)
    {
        $report = Report::findOrFail($id);
        $report->delete();
        return $this->showMessage('Report deleted successfully !!');
    }
}
