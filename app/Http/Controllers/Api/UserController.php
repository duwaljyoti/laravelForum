<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index()
    {
        $query = request('q');

        return User::where('name', 'like', "%". $query ."%")
            ->take(5)
            ->pluck('name');
    }
}
