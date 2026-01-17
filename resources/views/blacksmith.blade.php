@extends('layouts.app')

@section('content')
<div class="container">
    <div class="text-center mb-5">
        <h1 class="fw-bold">Blacksmith</h1>
        <p class="text-muted">Tingkatkan perlengkapanmu untuk mencapai Tier Diamond!</p>

        @if (session('success'))
        <div class="alert alert-success d-inline-block shadow-sm">{{ session('success') }}</div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger d-inline-block shadow-sm">{{ session('error') }}</div>
        @endif
    </div>

    <div class="row justify-content-center">


        <div class="col-md-5 mb-4">
            <div class="card shadow h-100 border-{{ $senjata['tier']['warna'] }}" style="border-width: 2px;">

                <div class="card-header bg-{{ $senjata['tier']['warna'] }} text-white text-center">
                    <h4 class="mb-0">⚔️ Senjata {{ $senjata['tier']['nama'] }}</h4>
                </div>

                <div class="card-body text-center">
                    <div class="display-1 my-3">{{ $senjata['tier']['icon'] }}</div>

                    <h5 class="text-muted">Level: <strong>{{ $senjata['level'] }}</strong></h5>

                    <div class="alert alert-light border">
                        <small class="text-muted text-uppercase">Bonus Serangan</small>
                        <div class="d-flex justify-content-center align-items-center mt-1">
                            <h3 class="text-secondary mb-0 me-3">{{ $senjata['bonus_skrg'] }}</h3>
                            <span class="fs-4">➡️</span>
                            <h3 class="text-success mb-0 ms-3">{{ $senjata['bonus_next'] }}</h3>
                        </div>
                    </div>

                    <hr>

                    @if($senjata['level'] < 100)
                        <p class="mb-1 text-muted">Biaya:</p>
                        <h4 class="text-warning fw-bold mb-3">💰 {{ number_format($senjata['biaya']) }}</h4>

                        <form action="{{ route('upgrade.item') }}" method="POST">
                            @csrf
                            <input type="hidden" name="tipe" value="senjata">
                            <button type="submit" class="btn btn-outline-{{ $senjata['tier']['warna'] }} btn-lg w-100 fw-bold">
                                Asah Senjata
                            </button>
                        </form>
                        @else
                        <div class="alert alert-success fw-bold">MAX LEVEL REACHED 💎</div>
                        @endif
                </div>
            </div>
        </div>


        <div class="col-md-5 mb-4">
            <div class="card shadow h-100 border-{{ $armor['tier']['warna'] }}" style="border-width: 2px;">

                <div class="card-header bg-{{ $armor['tier']['warna'] }} text-white text-center">
                    <h4 class="mb-0">🛡️ Armor {{ $armor['tier']['nama'] }}</h4>
                </div>

                <div class="card-body text-center">
                    <div class="display-1 my-3">{{ $armor['tier']['icon'] }}</div>

                    <h5 class="text-muted">Level: <strong>{{ $armor['level'] }}</strong></h5>

                    <div class="alert alert-light border">
                        <small class="text-muted text-uppercase">Bonus Pertahanan</small>
                        <div class="d-flex justify-content-center align-items-center mt-1">
                            <h3 class="text-secondary mb-0 me-3">{{ $armor['bonus_skrg'] }}</h3>
                            <span class="fs-4">➡️</span>
                            <h3 class="text-success mb-0 ms-3">{{ $armor['bonus_next'] }}</h3>
                        </div>
                    </div>

                    <hr>

                    @if($armor['level'] < 100)
                        <p class="mb-1 text-muted">Biaya:</p>
                        <h4 class="text-warning fw-bold mb-3">💰 {{ number_format($armor['biaya']) }}</h4>

                        <form action="{{ route('upgrade.item') }}" method="POST">
                            @csrf
                            <input type="hidden" name="tipe" value="armor">
                            <button type="submit" class="btn btn-outline-{{ $armor['tier']['warna'] }} btn-lg w-100 fw-bold">
                                Perkuat Armor
                            </button>
                        </form>
                        @else
                        <div class="alert alert-success fw-bold">MAX LEVEL REACHED 💎</div>
                        @endif
                </div>
            </div>
        </div>

    </div>

    <div class="text-center mt-4">
        <h5>Sisa Emas Kamu: <span class="badge bg-warning text-dark">{{ number_format($user->akun->emas) }}</span></h5>
    </div>
</div>
@endsection