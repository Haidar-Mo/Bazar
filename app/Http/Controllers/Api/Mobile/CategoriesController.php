<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
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
        $Categories = Category::query()->parent()->with(['children'])->get()->append('icon_path');
        return $this->showResponse($Categories, 'done successfully.....!');
    }

    public function show(string $id)
    {
        try {
            $category = Category::with(['children'])->findOrFail($id);
            return $this->showResponse($category, 'done successfully....!');
        } catch (Exception $e) {
            return $this->showError($e, 'something goes wrong....!');
        }
    }

}
