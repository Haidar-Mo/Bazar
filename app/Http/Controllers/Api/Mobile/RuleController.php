<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rule;
use App\Traits\ResponseTrait;
use App\Http\Requests\{
    RuleRequest,
    RuleUpdateRequest
};
use Whoops\Run;

class RuleController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        return $this->showResponse(Rule::get(),'done');
    }

}
