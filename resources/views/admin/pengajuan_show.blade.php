@extends('layouts.app')
@section('title', 'Admin - Detail Pengajuan')

@section('content')
<div style="max-width:900px;margin:24px auto;padding:0 16px;">

  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:16px;padding:18px;">
    <h2 style="margin:0 0 10px;font-weight:900;">Detail Pengajuan #{{ $pengajuan->id }}</h2>

    <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:12px;">
      <div>
        <div style="font-weight:800;">Status Kemahasiswaan:</div>
        <span style="display:inline-flex;padding:6px 10px;border-radius:999px;border:1px solid #e5e7eb;">
          {{ $kemaStatus }}
        </span>
      </div>

      <div>
        <div style="font-weight:800;">Status Admin:</div>
        <span style="display:inline-flex;padding:6px 10px;border-radius:999px;border:1px solid #e5e7eb;">
          {{ $pengajuan->status_admin ?? $pengajuan->status ?? '-' }}
        </span>
      </div>
    </div>

    {{-- Info utama pengajuan (contoh) --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
      <div>
        <div style="font-weight:800;">Peminjam</div>
        <div>{{ $pengajuan->email ?? '-' }}</div>
      </div>
      <div>
        <div style="font-weight:800;">Judul/Kegiatan</div>
        <div>{{ $pengajuan->title ?? '-' }}</div>
      </div>
      <div>
        <div style="font-weight:800;">Mulai</div>
        <div>{{ $pengajuan->start_time ?? '-' }}</div>
      </div>
      <div>
        <div style="font-weight:800;">Selesai</div>
        <div>{{ $pengajuan->end_time ?? '-' }}</div>
      </div>
    </div>

    <hr style="border:none;border-top:1px solid #eef2f7;margin:16px 0;">

    {{-- Catatan kemahasiswaan --}}
    <div>
      <div style="font-weight:900;margin-bottom:6px;">Catatan Kemahasiswaan</div>
      <div style="color:#6b7280;font-size:12px;margin-bottom:8px;">
        {{ $kemaAt ? 'Update: '.$kemaAt : 'Belum ada update' }}
      </div>

      <textarea readonly
        style="width:100%;min-height:120px;border:1px solid #e5e7eb;border-radius:12px;padding:10px;"
      >{{ $kemaNote ?? 'Belum ada catatan dari Kemahasiswaan.' }}</textarea>
    </div>

    <hr style="border:none;border-top:1px solid #eef2f7;margin:16px 0;">

    {{-- Tombol aksi admin --}}
    @php
      $kemaApproved = strtolower($kemaStatus) === 'disetujui';
    @endphp

    @if(!$kemaApproved)
      <div style="padding:10px 12px;border-radius:12px;background:#fffbeb;border:1px solid #fde68a;color:#92400e;font-weight:800;">
        Kemahasiswaan belum menyetujui. Admin belum bisa melakukan persetujuan.
      </div>
    @endif

    <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:12px;">
      <form method="POST" action="{{ route('admin.pengajuan.approve', $pengajuan->id) }}">
        @csrf
        <button type="submit"
          style="padding:10px 14px;border-radius:12px;border:1px solid #e5e7eb;background:#2563eb;color:#fff;font-weight:900;cursor:pointer;"
          {{ $kemaApproved ? '' : 'disabled' }}
        >
          Setujui (Admin)
        </button>
      </form>

      <form method="POST" action="{{ route('admin.pengajuan.reject', $pengajuan->id) }}">
        @csrf
        <button type="submit"
          style="padding:10px 14px;border-radius:12px;border:1px solid #e5e7eb;background:#ef4444;color:#fff;font-weight:900;cursor:pointer;"
        >
          Tolak
        </button>
      </form>

      <a href="{{ route('admin.pengajuan') }}"
        style="padding:10px 14px;border-radius:12px;border:1px solid #e5e7eb;background:#fff;color:#111827;font-weight:900;text-decoration:none;"
      >
        Kembali
      </a>
    </div>

  </div>
</div>
@endsection
