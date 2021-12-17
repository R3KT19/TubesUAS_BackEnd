<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use App\Models\User;


use Illuminate\Routing\Controller;

class EmailVerificationController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $user = User::find($request->route('id'));

        if ($user->hasVerifiedEmail()) {
            return redirect(env('http://localhost:8080/login') . '/email/verify/already-success');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        
        return redirect()->to('http://localhost:8080/login?verified=success');
    }
}
