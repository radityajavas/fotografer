<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Package;
use App\Models\Photographer;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    // Jam operasional (menit sejak 00:00): 08:00 - 20:00
    private const OPEN_MINUTES  = 8 * 60;
    private const CLOSE_MINUTES = 20 * 60;

    public function index(Request $request)
    {
        $status = $request->query('status');

        $query = Booking::with(['user', 'photographer', 'package']);

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $bookings = $query->latest()->paginate(10);

        $stats = [
            'total'      => Booking::count(),
            'pending'    => Booking::where('status', 'pending')->count(),
            'diterima'   => Booking::where('status', 'diterima')->count(),
            'ditolak'    => Booking::where('status', 'ditolak')->count(),
            'pendapatan' => Booking::with('package')
                ->where('status', 'diterima')
                ->get()
                ->sum(fn ($b) => $b->package->price ?? 0),
        ];

        // Untuk form "Booking Baru"
        $customers     = User::where('role', 'customer')->orderBy('name')->get();
        $photographers = Photographer::orderBy('name')->get();
        $packages      = Package::orderBy('price')->get();

        return view('admin.bookings.index', compact('bookings', 'stats', 'status', 'customers', 'photographers', 'packages'));
    }

    // Buat booking baru untuk pelanggan yang sudah punya akun
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id'         => ['required', Rule::exists('users', 'id')->where('role', 'customer')],
            'photographer_id' => ['required', 'exists:photographers,id'],
            'package_id'      => ['required', 'exists:packages,id'],
            'booking_date'    => ['required', 'date', 'after_or_equal:today', 'before_or_equal:' . today()->addYears(2)->toDateString()],
            'start_time'      => ['required', 'date_format:H:i'],
            'lokasi'          => ['required', 'string', 'min:10', 'max:400'],
        ], [
            'user_id.required'             => 'Pilih pelanggan terlebih dahulu.',
            'user_id.exists'               => 'Pelanggan tidak ditemukan.',
            'lokasi.min'                   => 'Alamat terlalu singkat, tulis minimal 10 karakter (jalan, nomor, patokan).',
            'booking_date.after_or_equal'  => 'Tanggal booking tidak boleh sebelum hari ini.',
            'booking_date.before_or_equal' => 'Tanggal pelaksanaan maksimal 2 tahun dari sekarang.',
        ]);

        $photographer = Photographer::findOrFail($data['photographer_id']);
        $package      = Package::findOrFail($data['package_id']);

        if (strtoupper($photographer->status) !== 'AVAILABLE') {
            return back()->withErrors(['photographer_id' => 'Fotografer ini sedang tidak menerima pesanan.'])->withInput();
        }

        // Jam selesai dihitung dari durasi paket
        [$h, $m]  = array_map('intval', explode(':', $data['start_time']));
        $startMin = $h * 60 + $m;
        $endMin   = $startMin + ((int) $package->duration_hours) * 60;

        if ($startMin < self::OPEN_MINUTES) {
            return back()->withErrors(['start_time' => 'Jam mulai paling awal 08.00.'])->withInput();
        }

        if ($endMin > self::CLOSE_MINUTES) {
            return back()->withErrors(['start_time' => 'Sesi ' . $package->duration_hours . ' jam dari jam itu melewati jam operasional (selesai maksimal 20.00).'])->withInput();
        }

        $start = $data['start_time'];
        $end   = sprintf('%02d:%02d', intdiv($endMin, 60), $endMin % 60);

        // Fotografer libur pada tanggal/jam itu
        $libur = Schedule::where('photographer_id', $photographer->id)
            ->overlap($data['booking_date'], $start, $end)
            ->exists();

        if ($libur) {
            return back()->withErrors(['booking_date' => 'Fotografer tidak tersedia pada tanggal/jam ini.'])->withInput();
        }

        // Jam itu sudah dipesan (sudah diterima)
        $sudahDipesan = Booking::where('photographer_id', $photographer->id)
            ->whereIn('status', ['diterima', 'confirmed'])
            ->overlap($data['booking_date'], $start, $end)
            ->exists();

        if ($sudahDipesan) {
            return back()->withErrors(['booking_date' => 'Tanggal/jam ini sudah dipesan pelanggan lain.'])->withInput();
        }

        // Hindari pesanan ganda dari pelanggan yang sama di jam yang bertabrakan
        $pesananGanda = Booking::where('user_id', $data['user_id'])
            ->where('photographer_id', $photographer->id)
            ->whereIn('status', ['pending', 'diterima', 'confirmed'])
            ->overlap($data['booking_date'], $start, $end)
            ->exists();

        if ($pesananGanda) {
            return back()->withErrors(['booking_date' => 'Pelanggan ini sudah punya pesanan dengan fotografer yang sama di tanggal/jam yang bertabrakan.'])->withInput();
        }

        Booking::create([
            'user_id'         => $data['user_id'],
            'photographer_id' => $photographer->id,
            'package_id'      => $data['package_id'],
            'booking_date'    => $data['booking_date'],
            'start_time'      => $start,
            'end_time'        => $end,
            'lokasi'          => trim($data['lokasi']),
            'status'          => 'pending',
        ]);

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking berhasil dibuat (status: Menunggu).');
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:diterima,ditolak',
        ], [
            'status.required' => 'Status wajib diisi.',
            'status.in'       => 'Status tidak valid.',
        ]);

        $booking   = Booking::findOrFail($id);
        $newStatus = $validated['status'];

        // 1. Status final (diterima/ditolak) tidak boleh diubah lagi
        if (in_array(strtolower($booking->status), ['diterima', 'ditolak'], true)) {
            return redirect()->back()->with('error', 'Status pemesanan sudah ' . $booking->status . ' dan tidak bisa diubah lagi.');
        }

        // 2. Validasi khusus saat menyetujui booking
        if ($newStatus === 'diterima') {
            // Tanggal foto sudah lewat
            if ($booking->booking_date && \Carbon\Carbon::parse($booking->booking_date)->isPast()
                && !\Carbon\Carbon::parse($booking->booking_date)->isToday()) {
                return redirect()->back()->with('error', 'Booking tidak bisa disetujui karena tanggal foto sudah lewat.');
            }

            // Fotografer menandai tanggal/jam itu tidak tersedia
            $libur = Schedule::where('photographer_id', $booking->photographer_id)
                ->overlap($booking->booking_date, $booking->start_time, $booking->end_time)
                ->exists();

            if ($libur) {
                return redirect()->back()->with('error', 'Booking tidak bisa disetujui: fotografer menandai tanggal/jam itu tidak tersedia.');
            }

            // Jam bentrok dengan booking lain yang sudah diterima
            $bentrok = Booking::where('photographer_id', $booking->photographer_id)
                ->where('status', 'diterima')
                ->where('id', '!=', $booking->id)
                ->overlap($booking->booking_date, $booking->start_time, $booking->end_time)
                ->exists();

            if ($bentrok) {
                return redirect()->back()->with('error', 'Jadwal bentrok: fotografer ini sudah punya booking yang diterima di tanggal/jam yang bertabrakan.');
            }
        }

        $booking->update(['status' => $newStatus]);

        return redirect()->back()->with('success', 'Status pemesanan berhasil diperbarui!');
    }
}