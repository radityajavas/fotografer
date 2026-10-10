<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Photographer;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    // Status booking yang dianggap "memakai" jam (menunggu atau sudah diterima)
    private const ACTIVE_STATUSES = ['menunggu', 'diterima'];

    public function index()
    {
        $photographers = Photographer::with([
            'bookings' => function ($q) {
                $q->with(['user', 'package'])
                  ->whereIn('status', self::ACTIVE_STATUSES)
                  ->whereDate('booking_date', '>=', today())
                  ->orderBy('booking_date')
                  ->orderBy('start_time');
            },
            'schedules' => function ($q) {
                $q->whereDate('date', '>=', today())
                  ->orderBy('date')
                  ->orderBy('start_time');
            },
        ])->get();

        return view('admin.schedule.index', compact('photographers'));
    }

    // TAMBAH (jam kosong = libur seharian)
    public function store(Request $request)
    {
        $data = $request->validate([
            'photographer_id' => 'required|exists:photographers,id',
            'date'            => 'required|date|after_or_equal:today',
            'start_time'      => 'nullable|date_format:H:i|required_with:end_time',
            'end_time'        => 'nullable|date_format:H:i|required_with:start_time|after:start_time',
            'note'            => 'nullable|string|max:255',
        ], [
            'end_time.after' => 'Jam selesai harus setelah jam mulai.',
        ]);

        $start = $data['start_time'] ?? null;
        $end   = $data['end_time'] ?? null;

        if ($this->sudahDiblokir($data['photographer_id'], $data['date'], $start, $end)) {
            return back()->withErrors(['date' => 'Tanggal/jam itu sudah ditandai tidak tersedia untuk fotografer ini.'])->withInput();
        }

        if ($this->adaBooking($data['photographer_id'], $data['date'], $start, $end)) {
            return back()->withErrors(['date' => 'Tidak bisa diblokir: sudah ada booking (menunggu/diterima) pada tanggal/jam itu.'])->withInput();
        }

        Schedule::create($data);

        return back()->with('success', 'Tanggal tidak tersedia berhasil ditambahkan!');
    }

    // UBAH
    public function update(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);

        $data = $request->validate([
            'date'       => 'required|date|after_or_equal:today',
            'start_time' => 'nullable|date_format:H:i|required_with:end_time',
            'end_time'   => 'nullable|date_format:H:i|required_with:start_time|after:start_time',
            'note'       => 'nullable|string|max:255',
        ], [
            'end_time.after' => 'Jam selesai harus setelah jam mulai.',
        ]);

        $start = $data['start_time'] ?? null;
        $end   = $data['end_time'] ?? null;

        if ($this->sudahDiblokir($schedule->photographer_id, $data['date'], $start, $end, $schedule->id)) {
            return back()->withErrors(['date' => 'Tanggal/jam itu sudah ditandai tidak tersedia untuk fotografer ini.'])->withInput();
        }

        if ($this->adaBooking($schedule->photographer_id, $data['date'], $start, $end)) {
            return back()->withErrors(['date' => 'Tidak bisa diubah ke tanggal/jam itu: sudah ada booking (menunggu/diterima).'])->withInput();
        }

        // Jam kosong berarti libur seharian
        $schedule->update([
            'date'       => $data['date'],
            'start_time' => $start,
            'end_time'   => $end,
            'note'       => $data['note'] ?? null,
        ]);

        return back()->with('success', 'Tanggal tidak tersedia berhasil diperbarui!');
    }

    // HAPUS
    public function destroy($id)
    {
        Schedule::findOrFail($id)->delete();

        return back()->with('success', 'Tanggal tidak tersedia berhasil dihapus!');
    }

    private function sudahDiblokir($photographerId, $date, $start, $end, $exceptId = null): bool
    {
        return Schedule::where('photographer_id', $photographerId)
            ->overlap($date, $start, $end)
            ->when($exceptId, fn ($q) => $q->where('id', '!=', $exceptId))
            ->exists();
    }

    private function adaBooking($photographerId, $date, $start, $end): bool
    {
        return Booking::where('photographer_id', $photographerId)
            ->whereIn('status', self::ACTIVE_STATUSES)
            ->overlap($date, $start, $end)
            ->exists();
    }
}