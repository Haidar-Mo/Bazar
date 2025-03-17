<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Traits\ResponseTrait;
use Exception;
class CategoriesController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Categories = Category::query()->parent()->get();
        return $this->showResponse($Categories,'done successfully.....!');


    }


    public function show(string $id)
    {
        try {
            $category = Category::with(['children'])->findOrFail($id);
            return $this->showResponse($category, 'done successfully....!');
        } catch(Exception $e) {
            return $this->showError($e, 'something goes wrong....!');
        }
    }


/*
    public function show(string $id)
    {
        try {
            $category = Category::with(['children'])->findOrFail($id);
            $ads = $category->ads()
                ->with(['images','category', 'city'])
                ->where('status','active')
                ->paginate(10);
            $category->setRelation('ads', $ads);
            return $this->showResponse($category, 'done successfully....!');
        } catch(Exception $e) {
            return $this->showError($e, 'something goes wrong....!');
        }
    }*/


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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
