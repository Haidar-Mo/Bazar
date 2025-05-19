<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\{
    ResponseTrait,
    UpdatePermissions
};
use App\Models\{
    User,
};
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Http\Requests\{UpdatePermissionRequest, CreateOfficialsRequest};
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\{DB, Auth};


class OfficialsController extends Controller
{
    use ResponseTrait, UpdatePermissions;

    public function index()
    {
        DB::beginTransaction();
        try {

            $supervisors = User::whereHas('roles', function ($query) {
                $query->whereIn('name', ['supervisor']);
            })->get();

            DB::commit();
            return $this->showResponse($supervisors, 'done successfully....!');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->showError($e, 'Something goes wrong....!');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateOfficialsRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = User::create(array_merge($request->validated(), [
                'is_verified' => true,
                'email_verified_at' => Carbon::now(),
            ]));

            $user->assignRole(Role::where('name', 'supervisor')->where('guard_name', 'api')->first());
            $user->subscriptions()->delete();
            $user->NotificationSettings()->delete();
            DB::commit();
            return $this->showResponse($user, 'Official add successfully....!');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->showError($e, 'something goes wrong...!');
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        return $this->showResponse($user->append('all_permissions'), 'done successfully...!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePermissionRequest $request, string $id)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
            $user->syncPermissions([]);
            $this->update_permission($request, $user);
            DB::commit();
            return $this->showMessage('permissions updated successfully...!');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->showError($e, 'something goes wrong....!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, string $permission)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
            $user->delete();
            DB::commit();
            return $this->showMessage('deleted done successfully...!');
        } catch (Exception $e) {
            return $this->showError($e, 'something goes wrong....!');

        }
    }


    public function addPermission(string $id, Request $request)
    {
        $request->validate([
            'name' => 'required|exists:permissions,name',
        ]);
        $permissionId = Permission::where('name', $request->name)->first()->id;
        $user = User::findOrFail($id);

        $user->permissions()->syncWithoutDetaching([$permissionId]);

        $user->load('permissions');

        return $this->showResponse($user->append('all_permissions'), 'The permission added successfully !!');
    }

    public function removePermission(string $id, Request $request)
    {
        $request->validate([
            'name' => 'required|exists:permissions,name',
        ]);

        $user = User::findOrFail($id);
        $permissionId = Permission::where('name', $request->name)->first()->id;

        $user->permissions()->detach($permissionId);

        $user->load('permissions');

        return $this->showResponse($user->append('all_permissions'), 'The permission deleted successfully !!');
    }

}
