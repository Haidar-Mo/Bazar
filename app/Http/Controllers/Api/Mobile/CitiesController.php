<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Filters\CitiesFilter;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class CitiesController extends Controller
{
    use ResponseTrait;

    public function __construct(protected CitiesFilter $citiesFilter)
    {
    }
    public function index(Request $request)
    {
        $cities = City::query();
        return $this->showResponse($this->citiesFilter->apply($cities)->get());
    }
}
