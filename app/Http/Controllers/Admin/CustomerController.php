<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class CustomerController extends Controller
{
    private const PHONE_REGEX = '/^\+?[0-9][0-9\s\-]{7,18}$/';

    public function index(Request $request)
    {
        $q = trim((string) $request->query('q'));

        $pelanggan = User::where('role', 'customer')
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($w) use ($q) {
                    $w->where('name', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%")
                      ->orWhere('phone', 'like', "%{$q}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.customers.index', compact('pelanggan', 'q'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'min:3', 'max:100', 'regex:/^[\pL\s.\'-]+$/u'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone'    => ['nullable', 'string', 'regex:' . self::PHONE_REGEX],
            'address'  => ['nullable', 'string', 'max:500'],
            'password' => ['required', 'string', Password::min(8)->letters()->numbers()],
        ], $this->messages());

        $user = new User();
        $user->username = $this->makeUsername($data['email']);
        $user->name     = trim($data['name']);
        $user->email    = mb_strtolower(trim($data['email']));
        $user->phone    = $data['phone'] ?? null;
        $user->address  = $data['address'] ?? null;
        $user->role     = 'customer';
        $user->password = Hash::make($data['password']);
        $user->save();

        return redirect()->route('admin.customers.index')->with('success', 'Pelanggan berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $customer = User::where('role', 'customer')->findOrFail($id);

        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $customer = User::where('role', 'customer')->findOrFail($id);

        $data = $request->validate([
            'name'     => ['required', 'string', 'min:3', 'max:100', 'regex:/^[\pL\s.\'-]+$/u'],
            'email'    => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($customer->id)],
            'phone'    => ['nullable', 'string', 'regex:' . self::PHONE_REGEX],
            'address'  => ['nullable', 'string', 'max:500'],
            'password' => ['nullable', 'string', Password::min(8)->letters()->numbers()],
        ], $this->messages());

        $customer->name    = trim($data['name']);
        $customer->email   = mb_strtolower(trim($data['email']));
        $customer->phone   = $data['phone'] ?? null;
        $customer->address = $data['address'] ?? null;

        // Kata sandi hanya diganti kalau kolomnya diisi
        if (! empty($data['password'])) {
            $customer->password = Hash::make($data['password']);
        }

        $customer->save();

        return redirect()->route('admin.customers.index')->with('success', 'Data pelanggan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        User::where('role', 'customer')->findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Pelanggan berhasil dihapus!');
    }

    private function messages(): array
    {
        return [
            'name.regex'  => 'Nama hanya boleh berisi huruf, spasi, titik, apostrof, dan tanda hubung.',
            'phone.regex' => 'Nomor telepon tidak valid. Gunakan angka saja, contoh: 081234567890.',
        ];
    }

    private function makeUsername(string $email): string
    {
        $base = Str::slug(Str::before($email, '@'), '') ?: 'user';
        $username = $base;
        $i = 1;
        while (User::where('username', $username)->exists()) {
            $username = $base . $i++;
        }

        return $username;
    }
}
