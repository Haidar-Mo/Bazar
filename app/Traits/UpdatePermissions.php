<?php
namespace App\Traits;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\BaseController;
trait UpdatePermissions{
    public function update_permission($request,$user){

        $permissions = $request->input('permissions');
        $existing_PermissionIds = Permission::whereIn('name', $permissions)->pluck('id')->toArray();
        $user->permissions()->sync($existing_PermissionIds);
    }
}
