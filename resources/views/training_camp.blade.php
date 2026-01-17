@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">

            {{-- Alert Pesan Sukses/Gagal --}}
            @if (session('success'))
            <div class="alert alert-success fw-bold text-center">{{ session('success') }}</div>
            @endif
            @if (session('error'))
            <div class="alert alert-danger fw-bold text-center">{{ session('error') }}</div>
            @endif

            <div class="card shadow-lg text-center border-0">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0">🏕️ Training Camp</h4>
                </div>

                <div class="card-body bg-light">
                    <h5 class="text-muted mb-4">Latih Pahlawanmu Agar Lebih Kuat!</h5>

                    {{-- Lingkaran Level Besar --}}
                    <div class="my-4">
                        <div class="d-inline-block border border-5 border-warning rounded-circle p-4 bg-white" style="width: 190px; height: 150px; display: flex; align-items: center; justify-content: center;">
                            <div>
                                <small class="text-muted d-block text-uppercase">Level</small>
                                <span class="display-3 fw-bold text-dark">{{ $user->akun->level }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Info Bonus Stat --}}
                    <div class="alert alert-info d-inline-block">
                        <strong>Bonus Level Saat Ini:</strong><br>
                        Serangan +{{ $user->akun->level * 2 }} | Pertahanan +{{ $user->akun->level * 2 }}
                    </div>

                    <hr>

                    {{-- Logika Tombol Upgrade --}}
                    @if($user->akun->level < 100)
                        <p class="mb-2">Biaya untuk naik ke Level <strong>{{ $user->akun->level + 1 }}</strong>:</p>
                        <h3 class="text-warning fw-bold mb-3">💰 {{ number_format($biayaUpgrade) }} Emas</h3>

                        <form action="{{ route('upgrade.process') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success btn-lg px-5 shadow">
                                ⬆️ UPGRADE LEVEL
                            </button>
                        </form>
                        @else
                        <div class="alert alert-success">
                            🏆 <strong>MAX LEVEL</strong><br>
                            Kamu telah mencapai batas kekuatan tertinggi!
                        </div>
                        @endif

                </div>
                <div class="card-footer text-muted">
                    Sisa Emas Kamu: <strong>{{ number_format($user->akun->emas) }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection