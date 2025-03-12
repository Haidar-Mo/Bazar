<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Dashboard\UserService;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ResponseTrait;


    public function __construct(protected UserService $service)
    {
    }

    /**
     * Display specific User
     * @param string $id
     * @return User|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        try {
            return User::with('ads')->findOrFail($id)->append('hasVerificationRequest');
        } catch (Exception $e) {
            report($e);
            return $this->showError($e, 'An error occur while show user details');
        }
    }

    /**
     * Display All users with Filter
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {

        try {
            $users = $this->service->index();
            return $this->showResponse($users, 'Users retrieved successfully!!', 200);
        } catch (Exception $e) {
            report($e);
            return $this->showError($e, 'An error occur while get users', 500);
        }
    }


    /**
     * Block specific user
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function block(string $id)
    {
        try {
            $user = User::findOrFail($id);
            $this->service->block($id);
            return $this->showResponse($user, 'User blocked successfully !!', 200);
        } catch (Exception $e) {
            report($e);
            return $this->showError($e, 'An error occur while blocking the user', $e->getCode() ?? 500);
        }
    }


    /**
     * Unblock specific user
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function unblock(string $id)
    {
        try {
            $user = $this->service->unblock($id);
            return $this->showResponse($user, 'User Unblocked successfully !!', 200);
        } catch (Exception $e) {
            report($e);
            return $this->showError($e, 'An error occur while blocking the user', $e->getCode() ?? 500);
        }
    }



}

