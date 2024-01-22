<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Report\TotalSalesHelper;
use App\Helpers\User\AuthHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\AuthRequest;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private $sales;
    public function __construct()
    {
        $this->sales = new TotalSalesHelper;
    }
    public function login(AuthRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors(), 422);
        }

        $credentials = $request->only('email', 'password');
        $login = AuthHelper::login($credentials['email'], $credentials['password']);

        if (!$login['status']) {
            return response()->failed($login['error'], 422);
        }

        return response()->success($login['data']);
    }

    public function profile()
    {
        return response()->success(new UserResource(auth()->user()));
    }

    public function getTotalSummary()
    {

        $sales = $this->sales->getTotalInPeriode();

        return response()->success($sales['data']);

    }
}
