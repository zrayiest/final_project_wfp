@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4 fw-bold">📜 Laporan Pertempuran</h3>

    @forelse($laporans as $log)
    @php
    // Cek apakah kita Penyerang atau Korban?
    $sayaPenyerang = ($log->penyerang_id == $user->akun->id);
    $sayaMenang = ($log->pemenang_id == $user->akun->id);

    // Tentukan Warna Kartu
    // Menang = Hijau, Kalah = Merah
    $warna = $sayaMenang ? 'success' : 'danger';
    $icon = $sayaMenang ? '🏆 MENANG' : '💀 KALAH';
    @endphp

    <div class="card mb-3 border-{{ $warna }} shadow-sm">
        <div class="card-header bg-{{ $warna }} text-white d-flex justify-content-between">
            <span class="fw-bold">{{ $icon }}</span>
            <small>{{ $log->created_at->diffForHumans() }}</small>
        </div>
        <div class="card-body">
            <div class="row align-items-center">
                {{-- Info Lawan --}}
                <div class="col-md-8">
                    @if($sayaPenyerang)
                    <h5 class="card-title">Kamu menyerang <strong>{{ $log->musuh->nama }}</strong></h5>
                    @else
                    <h5 class="card-title">Kamu diserang oleh <strong>{{ $log->penyerang->nama }}</strong></h5>
                    @endif

                    <p class="card-text text-muted mb-0">
                        Pasukanmu gugur: <strong>{{ $sayaPenyerang ? $log->pasukan_mati_penyerang : $log->pasukan_mati_musuh }}</strong> |
                        Pasukan lawan gugur: <strong>{{ $sayaPenyerang ? $log->pasukan_mati_musuh : $log->pasukan_mati_penyerang }}</strong>
                    </p>
                </div>

                {{-- Info Emas --}}
                <div class="col-md-4 text-end">
                    @if($log->emas_jarahan > 0)
                    @if($sayaMenang && $sayaPenyerang)
                    <h4 class="text-success fw-bold">+{{ number_format($log->emas_jarahan) }} Emas</h4>
                    <small>Hasil Rampokan</small>
                    @elseif(!$sayaMenang && !$sayaPenyerang)
                    <h4 class="text-danger fw-bold">-{{ number_format($log->emas_jarahan) }} Emas</h4>
                    <small>Dicuri Lawan</small>
                    @else
                    <h4 class="text-muted">0 Emas</h4>
                    @endif
                    @else
                    <h4 class="text-muted">0 Emas</h4>
                    <small>Tidak ada jarahan</small>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="alert alert-info text-center">Belum ada sejarah pertempuran.</div>
    @endforelse
</div>
@endsection