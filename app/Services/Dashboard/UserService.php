<?php

namespace App\Services\Dashboard;

use App\Filters\Dashboard\UserFilter;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

/**
 * Class UserService.
 */
class UserService
{
    public function __construct(protected UserFilter $userFilter)
    {
    }

    public function index()
    {
        $query = User::query();
        return $this->userFilter->applyFilters($query)->get();
    }

    public function block(Request $request, string $id)
    {
        $request->validate([
            'block_reason' => 'nullable'
        ]);
        $user = User::findOrFail($id);
        if ($user->is_blocked)
            throw new Exception('User is already blocked...!', 422);
        $user->update([
            'is_blocked' => 1,
            'block_reason' => $request->block_reason
        ]);
        return $user;

    }

    public function unblock(string $id)
    {
        $user = User::findOrFail($id);
        if (!$user->is_blocked)
            throw new Exception('User is already unblocked...!', 400);
        $user->update([
            'is_blocked' => 0
        ]);
        return $user;
    }
}
