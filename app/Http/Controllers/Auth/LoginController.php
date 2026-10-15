<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    // Remove or comment out this line:
    // protected $redirectTo = '/home';

    protected function redirectTo()
    {
        if (Auth::user()->role === 'admin') {
            return '/admin';
        }

        return '/home';
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}