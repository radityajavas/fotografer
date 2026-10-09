<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'     => ['required', 'string', 'min:3', 'max:100', 'regex:/^[\pL\s.\'-]+$/u'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', Password::min(8)->letters()->numbers()],
        ], [
            'name.regex' => 'Nama hanya boleh berisi huruf, spasi, titik, apostrof, dan tanda hubung.',
        ]);
    }

    protected function create(array $data)
    {
        $base = Str::slug(Str::before($data['email'], '@'), '') ?: 'user';
        $username = $base;
        $i = 1;
        while (User::where('username', $username)->exists()) {
            $username = $base . $i++;
        }

        $user = new User();
        $user->username = $username;
        $user->name     = trim($data['name']);
        $user->email    = mb_strtolower(trim($data['email']));
        $user->role     = 'customer';
        $user->password = Hash::make($data['password']);
        $user->save();

        return $user;
    }

    protected function registered(Request $request, $user)
    {
        return redirect()->intended('/');
    }
}
