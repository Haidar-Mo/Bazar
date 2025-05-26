<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class CitiesController extends Controller
{
    use ResponseTrait;
    public function index()
    {
        $cities = City::all();
        return $this->showResponse($cities);
    }
}
