<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Traits\ResponseTrait;
use App\Http\Requests\{
    UpdateCategories,
    CreateCategoryRequest
};
use PhpParser\Node\Stmt\Catch_;

class CategoriesController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->showResponse(Category::all(), 'All categories retrieved !!');
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
    public function store(CreateCategoryRequest $request)
    {
        $category=Category::create($request->all());
        return $this->showResponse($category,'category created successfully...!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $this->showResponse($category=Category::with(['children'])->findOrFail($id),'done successfully') ;

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
    public function update(UpdateCategories $request, string $id)
    {
        $category=Category::findOrFail($id);
        $category->update($request->all());
        return $this->showResponse($category,'category updated successfully...!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category=Category::findOrFail($id);
        $category->delete();
        return $this->showMessage('Category deleted successfully...!');
    }
}
