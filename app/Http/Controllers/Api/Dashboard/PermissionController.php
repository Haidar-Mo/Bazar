<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Traits\{
    ResponseTrait,
    UpdatePermissions
};
use Illuminate\Support\Facades\{Auth,DB};
use Exception;
use App\Http\Requests\CreatePermissionsReuest;
class PermissionController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->showResponse(Permission::all()->pluck('name'),'done successfully....!');
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
    public function store(CreatePermissionsReuest $request)
    {
        DB::beginTransaction();
        try{
            foreach($request->permissions as $permission){
                Permission::create([

                    'name'=>$permission,
                    'guard_name'=>'api'

                ]);
            }
            DB::commit();
            return $this->showResponse($permission,'permission created successfully...!');

        }catch(Exception $e){
            DB::rollBack();
            return $this->showError($e,'something goes wrong....!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function update(Request $request, string $id)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try{
        $permission=Permission::findOrFail($id);
        $permission->delete();
        DB::commit();
        return $this->showMessage('permission deleted successfully...!');
        }catch(Exception $e){
            DB::rollBack();
            return $this->showError($e,'Something goes wrong....!');

        }
    }
}
