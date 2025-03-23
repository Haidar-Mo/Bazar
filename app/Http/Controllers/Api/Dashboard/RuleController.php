<?php

namespace App\Http\Controllers\Api\Dashboard;

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
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return $this->showResponse(Rule::get(),'done');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RuleRequest $request)
    {
        $rule=Rule::create($request->all());
        return $this->showResponse($rule,'create Done successfully....!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $rule=Rule::findOrFail($id);
        return $this->showResponse($rule,'done');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RuleUpdateRequest $request)
    {
        $rule_id=Rule::get()->first();
        $rule=Rule::find($rule_id->id);
        $rule->update($request->all());
        return $this->showResponse($rule,'update done successfully....!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rule=Rule::findOrFail($id);
        $rule->delete();
        return $this->showMessage('delete done successfully....!');
    }
}
