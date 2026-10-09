<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => ['required', 'string', 'max:255'],
            'password'        => ['required', 'string', 'max:255'],
        ], [
            'email.required'    => 'Email atau username wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);
    }

    // Form menerima "Email atau username": tentukan kolom yang dipakai.
    protected function credentials(Request $request)
    {
        $login  = trim($request->input($this->username()));
        $column = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        return [$column => $login, 'password' => $request->input('password')];
    }

    protected function authenticated(Request $request, $user)
    {
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->intended(route('landing'));
    }

    protected function redirectTo()
    {
        return auth()->user()->role === 'admin' ? '/admin/dashboard' : '/';
    }
}
