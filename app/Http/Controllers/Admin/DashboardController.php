<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Package;
use App\Models\Photographer;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'totalBooking'      => Booking::count(),
            'totalPelanggan'    => User::where('role', 'customer')->count(),
            'totalPhotographer' => Photographer::count(),
            'totalPackage'      => Package::count(),
            'latestBookings'    => Booking::with('user')->latest()->take(5)->get(),
        ]);
    }
}