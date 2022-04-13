<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ProfileController extends Controller
{
  public function update(ProfileUpdateRequest $request)
  {
    Auth::user()->update($request->validated());

    return Redirect::back()->with('success', 'Profile Updated');
  }
}
