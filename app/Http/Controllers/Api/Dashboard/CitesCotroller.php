<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;
use App\Traits\ResponseTrait;
use App\Http\Requests\{
    CreateCityRequest,
    UpdateCityRequest
};
class CitesCotroller extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->showResponse(City::get()->select('id','name'),'done successfully..!');
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
    public function store(CreateCityRequest $request)
    {
        $city=City::create($request->all());
        return $this->showResponse($city,'city created successfully..!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

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
    public function update(UpdateCityRequest $request, string $id)
    {
        $city=City::findOrFail($id);
        $city->update($request->all());
        return $this->showResponse($city,'city updated successfully...!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $city=City::findOrFail($id);
        $city->delete();
        return $this->showMessage('city deleted successfully...!');
    }
}
