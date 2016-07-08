<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests;

class UserController extends Controller
{
    public function updatePassword(Request $request) {

        $this->validate($request, [
            'password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        $user->fill([
            'password' => Hash::make($request->password)
        ])->save();

        return ['status' => 'success'];

    }
}
