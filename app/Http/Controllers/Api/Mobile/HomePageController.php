<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Advertisement,
    User
};
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\{Auth,DB};
class HomePageController extends Controller
{
    use ResponseTrait;



    public function index(Request $request)
    {
        $user=Auth::user();
        if($request->has('category_id') && $request->category_id!=''){
         $ads=Advertisement::with(['images','category','city'])->where('category_id',$request->category_id)->where('status','active')->orderBy('created_at','desc')->paginate(10);
        }
        else{
            $ads=Advertisement::with(['images','category','city'])->where('status','active')->orderBy('created_at','desc')->paginate(10);

        }
        return $this->showResponse($ads,'done successfully.....!');

    }



    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show(string $id)
    {
        //
    }


    public function edit(string $id)
    {
        //
    }


    public function update(Request $request, string $id)
    {
        //
    }


    public function destroy(string $id)
    {
        //
    }
}
