<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AvatarController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'avatar' => 'image'
        ]);

        auth()->user()->update([
           'avatar_path' => $request->file('avatar')->store('avatars', 'public')
        ]);

        return back();
    }
}
