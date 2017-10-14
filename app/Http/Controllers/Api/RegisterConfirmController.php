<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegisterConfirmController extends Controller
{
    public function confirm(Request $request)
    {
        try {
            User::where('confirmation_token', $request->get('token'))
                ->firstOrFail()
                ->confirm();
        } catch (ModelNotFoundException $exception) {
            return  redirect(route('threads'))
                ->with('flash', 'Invalid Token');
        }

        return redirect('/threads')
            ->with('flash', 'You are now authorised to create threads');
    }
}
