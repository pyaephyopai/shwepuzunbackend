<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function verify($id, Request $request)
    {
        if (!$request->hasValidSignature()) {

            return $this->respondUnAuthorizedRequest(253);
        }

        $user = User::where('id', $id)->first();

        if (!$user->hasVerifiedEmail()) {

            $user->markEmailAsVerified();
        }

        return redirect()->to('http://localhost:5173/login');
    }

    public function resend()
    {

        if (auth()->user()->hasVerifiedEmail()) {

            return $this->respondBadRequest(254);
        }

        auth()->user()->sendEmailVerificationNotification();

        return $this->respondWithMessage('Email verification link send on your email id');
    }
}
