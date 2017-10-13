<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegisterConfirmController extends Controller
{
    public function confirm(Request $request)
    {
        $user = User::where('confirmation_token', $request->get('token'))
            ->firstOrFail();

        $user->confirm();

        return redirect('/threads')
            ->with('flash', 'You are now authorised to create threads');
    }
}
