@extends('layouts.app')

@section('title', 'Admin - Detail Pengajuan')

@section('content')
  <div style="max-width:1000px;margin:20px auto;padding:0 16px;">
    <div style="background:#fff;border:1px solid #e5e7eb;border-radius:16px;padding:16px;">
      <h2 style="margin:0 0 10px;font-weight:900;">Detail Pengajuan #{{ $data->id }}</h2>
      <div><b>Kema Status:</b> {{ $data->kema_status }}</div>
      <div><b>Status Admin:</b> {{ $data->status }}</div>
    </div>
  </div>
@endsection
