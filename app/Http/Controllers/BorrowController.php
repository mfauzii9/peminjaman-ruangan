<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\BorrowRequest;
use App\Notifications\BorrowSubmitted;
use App\Notifications\KemaApproved;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BorrowController extends Controller
{
    // Diubah menjadi 0 agar bisa langsung meminjam saat ini juga
    private const MIN_ADVANCE_MINUTES = 0;
    private const MIN_DURATION_MINUTES = 30;
    private const MAX_DURATION_HOURS   = 8;

    private const ADMIN_EMAIL = 'rizkyfauzi040507@gmail.com';
    private const KEMA_EMAIL  = 'ikyyganz04@gmail.com';

    /**
     * Ambil semua interval yang mengunci ruangan.
     */
    private function getRoomLocks(Room $room, Carbon $now, string $tz)
    {
        // borrow_requests yang benar-benar sudah mengunci ruangan
        $borrowLocks = BorrowRequest::query()
            ->where('room_id', $room->id)
            ->whereIn('status', ['disetujui'])
            ->orderBy('start_time', 'asc')
            ->get([
                'start_time',
                'end_time',
                'status',
                'org_name',
                'responsible_name',
            ])
            ->map(function ($r) use ($tz) {
                $start = Carbon::parse($r->start_time)->timezone($tz);
                $end   = Carbon::parse($r->end_time)->timezone($tz);

                $who = $r->org_name ?: ($r->responsible_name ?: '-');

                return [
                    'start' => $start,
                    'end'   => $end,
                    'title' => 'ACC - ' . $who,
                    'src'   => 'borrow_requests',
                ];
            });

        $pbmLocks = DB::table('pbm_occurrences')
            ->where('room_id', $room->id)
            ->where('status', 'approved')
            ->orderBy('start_time', 'asc')
            ->get([
                'pbm_id',
                'start_time',
                'end_time',
                'status',
            ])
            ->map(function ($r) use ($tz) {
                $start = Carbon::parse($r->start_time)->timezone($tz);
                $end   = Carbon::parse($r->end_time)->timezone($tz);

                return [
                    'start' => $start,
                    'end'   => $end,
                    'title' => 'PBM #' . (string) $r->pbm_id,
                    'src'   => 'pbm_occurrences',
                ];
            });

        $blockLocks = DB::table('room_blocks')
            ->where('room_id', $room->id)
            ->where('status', 'terbooking')
            ->orderBy('start_time', 'asc')
            ->get([
                'title',
                'note',
                'start_time',
                'end_time',
                'status',
                'source',
            ])
            ->map(function ($r) use ($tz) {
                $start = Carbon::parse($r->start_time)->timezone($tz);
                $end   = Carbon::parse($r->end_time)->timezone($tz);

                $title = trim((string) ($r->title ?? ''));
                $note  = trim((string) ($r->note ?? ''));

                $labelCore = $title !== '' ? $title : ($note !== '' ? $note : 'Ruangan diblok');
                $srcLabel  = strtoupper((string) ($r->source ?? 'JADWAL'));

                return [
                    'start' => $start,
                    'end'   => $end,
                    'title' => 'BLOCK (' . $srcLabel . ') - ' . $labelCore,
                    'src'   => 'room_blocks',
                ];
            });

        return $borrowLocks
            ->concat($pbmLocks)
            ->concat($blockLocks)
            ->sortBy(function ($x) {
                return $x['start']->getTimestamp();
            })
            ->values();
    }

    public function create(Room $room)
    {
        $tz  = config('app.timezone', 'Asia/Jakarta');
        $now = Carbon::now($tz);

        $minStartLocal = $now->copy()
            ->addMinutes(self::MIN_ADVANCE_MINUTES)
            ->format('Y-m-d\TH:i');

        $locks = $this->getRoomLocks($room, $now, $tz);

        $roomSchedules = $locks->map(function ($x) {
            return [
                'start_ms' => $x['start']->getTimestampMs(),
                'end_ms'   => $x['end']->getTimestampMs(),
                'title'    => $x['title'],
            ];
        })->values();

        $startToday = $now->copy()->startOfDay();
        $endToday   = $now->copy()->endOfDay();

        $startTomorrow = $now->copy()->addDay()->startOfDay();
        $endTomorrow   = $now->copy()->addDay()->endOfDay();

        $todaySchedules = [];
        $tomorrowSchedules = [];
        $nextSchedules = [];

        foreach ($locks as $x) {
            $s = $x['start'];
            $e = $x['end'];

            $lineTodayTomorrow = $s->format('H:i') . ' - ' . $e->format('H:i') . ' — ' . $x['title'];
            $lineNext = $s->translatedFormat('d M') . ' • ' . $s->format('H:i') . ' - ' . $e->format('H:i') . ' — ' . $x['title'];

            if ($s->betweenIncluded($startToday, $endToday)) {
                if ($e->gt($now)) {
                    $todaySchedules[] = $lineTodayTomorrow;
                }
                continue;
            }

            if ($s->betweenIncluded($startTomorrow, $endTomorrow)) {
                $tomorrowSchedules[] = $lineTodayTomorrow;
                continue;
            }

            if ($s->gt($endTomorrow)) {
                $nextSchedules[] = $lineNext;
            }
        }

        sort($todaySchedules);
        sort($tomorrowSchedules);
        sort($nextSchedules);

        return view('borrow.create', [
            'room'             => $room,
            'minStartLocal'    => $minStartLocal,
            'roomSchedules'    => $roomSchedules,
            'todaySchedules'   => $todaySchedules,
            'tomorrowSchedules'=> $tomorrowSchedules,
            'nextSchedules'    => $nextSchedules,
        ]);
    }

    public function scheduleJson(Room $room)
    {
        $tz  = config('app.timezone', 'Asia/Jakarta');
        $now = Carbon::now($tz);

        $locks = $this->getRoomLocks($room, $now, $tz);

        $roomSchedules = $locks
            ->filter(function ($x) use ($now) {
                return $x['end']->gt($now);
            })
            ->map(function ($x) {
                return [
                    'start_ms' => $x['start']->getTimestampMs(),
                    'end_ms'   => $x['end']->getTimestampMs(),
                    'title'    => $x['title'],
                ];
            })
            ->values();

        return response()->json($roomSchedules);
    }

    public function store(Request $request, Room $room)
    {
        $validated = $request->validate([
            'email'            => ['required', 'email', 'max:120'],
            'phone'            => ['required', 'string', 'max:30'],
            'responsible_name' => ['required', 'string', 'max:120'],
            'org_name'         => ['required', 'string', 'max:150'],
            'start_time'       => ['required', 'date'],
            'end_time'         => ['required', 'date', 'after:start_time'],
            'letter'           => ['required', 'file', 'mimes:pdf', 'max:5120'],
        ], [
            'letter.required' => 'Surat peminjaman wajib diupload (PDF).',
            'letter.mimes'    => 'Format surat harus PDF.',
            'letter.max'      => 'Ukuran surat maksimal 5MB.',
        ]);

        $tz  = config('app.timezone', 'Asia/Jakarta');
        $now = Carbon::now($tz);

        $start = Carbon::parse($validated['start_time'], $tz);
        $end   = Carbon::parse($validated['end_time'], $tz);

        // Pastikan tidak memilih waktu di masa lalu
        if ($start->lt($now->copy()->addMinutes(self::MIN_ADVANCE_MINUTES))) {
            return back()
                ->withErrors(['start_time' => 'Waktu pengajuan tidak boleh di masa lalu.'])
                ->withInput();
        }

        if ($end->lte($start)) {
            return back()
                ->withErrors(['end_time' => 'Waktu selesai harus setelah waktu mulai.'])
                ->withInput();
        }

        if ($end->lt($start->copy()->addMinutes(self::MIN_DURATION_MINUTES))) {
            return back()
                ->withErrors(['end_time' => 'Durasi minimal 30 menit.'])
                ->withInput();
        }

        if ($end->gt($start->copy()->addHours(self::MAX_DURATION_HOURS))) {
            return back()
                ->withErrors(['end_time' => 'Durasi maksimal 8 jam.'])
                ->withInput();
        }

        $bentrokBorrow = BorrowRequest::query()
            ->where('room_id', $room->id)
            ->whereIn('status', ['disetujui'])
            ->where('start_time', '<', $end->format('Y-m-d H:i:s'))
            ->where('end_time', '>', $start->format('Y-m-d H:i:s'))
            ->exists();

        $bentrokPbm = DB::table('pbm_occurrences')
            ->where('room_id', $room->id)
            ->where('status', 'approved')
            ->where('start_time', '<', $end->format('Y-m-d H:i:s'))
            ->where('end_time', '>', $start->format('Y-m-d H:i:s'))
            ->exists();

        $bentrokBlock = DB::table('room_blocks')
            ->where('room_id', $room->id)
            ->where('status', 'terbooking')
            ->where('start_time', '<', $end->format('Y-m-d H:i:s'))
            ->where('end_time', '>', $start->format('Y-m-d H:i:s'))
            ->exists();

        // JIKA BENTROK, KIRIM SESSION KE SWEETALERT
        if ($bentrokBorrow || $bentrokPbm || $bentrokBlock) {
            return back()
                ->with('error_conflict', 'Maaf jadwal bentrok, Anda bisa lihat dulu di daftar jadwal di beranda Home.')
                ->withInput();
        }

        $path = $request->file('letter')->store('letters', 'public');

        $publicCode = $this->generatePublicCode();
        $tokenPlain = Str::random(64);
        $tokenHash  = hash('sha256', $tokenPlain);

        $borrow = BorrowRequest::create([
            'public_code'      => $publicCode,
            'token_hash'       => $tokenHash,
            'token_created_at' => now(),

            'room_id'          => $room->id,
            'email'            => $validated['email'],
            'responsible_name' => $validated['responsible_name'],
            'org_name'         => $validated['org_name'],
            'phone'            => $validated['phone'],

            'start_time'       => $start->format('Y-m-d H:i:s'),
            'end_time'         => $end->format('Y-m-d H:i:s'),
            'letter_file'      => 'storage/' . $path,

            'status'           => 'menunggu',
            'kema_status'      => 'menunggu',
        ]);

        $roomLabel = trim(($room->floor ?? '-') . ' - ' . ($room->name ?? '-'));
        $startText = $start->format('d M Y H:i');
        $endText   = $end->format('d M Y H:i');

        Notification::route('mail', $borrow->email)
            ->notify(new BorrowSubmitted($borrow->id, $roomLabel, $startText, $endText));

        Notification::route('mail', self::ADMIN_EMAIL)
            ->notify(new BorrowSubmitted($borrow->id, $roomLabel, $startText, $endText));

        Notification::route('mail', self::KEMA_EMAIL)
            ->notify(new KemaApproved($borrow->id, $roomLabel, $startText, $endText));

        return redirect()->route('success.show', [
            'code'  => $borrow->public_code,
            'token' => $tokenPlain,
        ])->with('ok', 'Pengajuan berhasil. Menunggu verifikasi Kemahasiswaan.');
    }

    private function generatePublicCode(): string
    {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

        for ($i = 0; $i < 10; $i++) {
            $code = 'lpkia-';

            for ($j = 0; $j < 6; $j++) {
                $code .= $chars[random_int(0, strlen($chars) - 1)];
            }

            if (!BorrowRequest::where('public_code', $code)->exists()) {
                return $code;
            }
        }

        abort(500, 'Gagal membuat kode booking.');
    }
}