<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    DB,
    Auth
};
use App\Http\Requests\RateRequest;
use App\Models\{
    User,
    Rate
};
use App\Traits\ResponseTrait;
use Exception;

class RateController extends Controller
{
    use ResponseTrait;



    /**
     * Give a rate for a user
     * @param \App\Http\Requests\RateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(RateRequest $request)
    {
        $user = Auth::user();
        DB::beginTransaction();
        try {
            Rate::create([
                'user_id' => $user->id,
                'rated_user_id' => $request->rated_user_id,
                'rate' => $request->rate,
                 'comment' => $request->comment ?: '',
            ]);
            DB::commit();
            return $this->showMessage('تم ارسال تقيمك بنجاح');

        } catch (Exception $e) {
            DB::rollBack();
            return $this->showError($e, 'حدث خطأ ما يرجى المحاولة لاحقا');

        }
    }


    /**
     * Send report for a specific rate
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function report(Request $request, string $id)
    {
        try {
            $request->validate([
                'paragraph' => ['nullable', 'string']
            ]);
            $rate = Rate::findOrFail($id);
            $report = Report::create([
                'user_id' => $request->user()->id,
                'reportable_type' => get_class($rate),
                'reportable_id' => $rate->id,
                'paragraph' => $request->paragraph
            ]);
            return $this->showResponse($report, 'Report sent successfully !!', 200);
        } catch (Exception $e) {
            report($e);
            return $this->showError($e, 'An error occur while reporting the comment !!');
        }
    }

}
