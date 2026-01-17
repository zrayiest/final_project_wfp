@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Profil Pemain</h5>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h4>{{ $user->name }}</h4>
                            <p class="text-muted">{{ $user->email }}</p>

                            @if($user->akun)
                            <span class="badge bg-warning text-dark">Emas: {{ $user->akun->emas }}</span>
                            <span class="badge bg-danger">Pasukan: {{ $user->akun->jumlah_pasukan_tersedia }}</span>
                            @else
                            <div class="alert alert-warning mt-2">
                                Kamu belum membuat akun game!
                            </div>
                            @endif
                        </div>
                    </div>

                    <hr>

                    @if($user->akun && $user->akun->suku)

                    <div class="mb-3">
                        <h5>Suku Pilihan:
                            <span class="text-primary fw-bold">{{ $user->akun->suku->nama }}</span>
                        </h5>
                    </div>

                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                            <h6 class="card-title text-muted mb-0">Statistik Karakter (Level {{ $user->akun->level }})</h6>
                            <span class="badge bg-primary">Bonus Atribut: +{{ $user->akun->level * 2 }}</span>
                        </div>


                        @php
                        $bonus = $user->akun->level * 2;
                        @endphp

                        <div class="row">

                            <div class="col-6 border-end">
                                <h6 class="text-danger fw-bold mb-3">⚔️ Serangan</h6>
                                <ul class="list-unstyled mb-0">
                                    <li class="d-flex justify-content-between mb-2">
                                        <span>Fisik (Melee)</span>
                                        <strong class="text-dark">
                                            {{ $user->akun->suku->serang_melee + $bonus }}
                                            <span class="text-success small">(+{{ $bonus }})</span>
                                        </strong>
                                    </li>
                                    <li class="d-flex justify-content-between mb-2">
                                        <span>Range</span>
                                        <strong class="text-dark">
                                            {{ $user->akun->suku->serang_range + $bonus }}
                                            <span class="text-success small">(+{{ $bonus }})</span>
                                        </strong>
                                    </li>
                                    <li class="d-flex justify-content-between">
                                        <span>Magic</span>
                                        <strong class="text-dark">
                                            {{ $user->akun->suku->serang_magic + $bonus }}
                                            <span class="text-success small">(+{{ $bonus }})</span>
                                        </strong>
                                    </li>
                                </ul>
                            </div>


                            <div class="col-6">
                                <h6 class="text-success fw-bold mb-3 ps-2">🛡️ Pertahanan</h6>
                                <ul class="list-unstyled mb-0 ps-2">
                                    <li class="d-flex justify-content-between mb-2">
                                        <span>Tahan Fisik</span>
                                        <strong class="text-dark">
                                            {{ $user->akun->suku->tahan_melee + $bonus }}
                                            <span class="text-success small">(+{{ $bonus }})</span>
                                        </strong>
                                    </li>
                                    <li class="d-flex justify-content-between mb-2">
                                        <span>Tahan Range</span>
                                        <strong class="text-dark">
                                            {{ $user->akun->suku->tahan_range + $bonus }}
                                            <span class="text-success small">(+{{ $bonus }})</span>
                                        </strong>
                                    </li>
                                    <li class="d-flex justify-content-between">
                                        <span>Tahan Magic</span>
                                        <strong class="text-dark">
                                            {{ $user->akun->suku->tahan_magic + $bonus }}
                                            <span class="text-success small">(+{{ $bonus }})</span>
                                        </strong>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title text-muted mb-3">Penampilan Karakter (Outfit)</h6>

                            <div class="row">
                                @forelse($user->akun->suku->outfits as $item)

                                @php

                                if ($item->bagian == 'tangan') {
                                $levelItem = $user->akun->level_senjata;
                                $tipe = 'Senjata';
                                } else {
                                $levelItem = $user->akun->level_armor;
                                $tipe = 'Armor';
                                }

                                $tier = $user->akun->getTierInfo($levelItem);
                                @endphp

                                <div class="col-md-6 mb-3">

                                    <div class="d-flex align-items-center border border-{{ $tier['warna'] }} bg-white p-2 rounded h-100 shadow-sm">


                                        <div class="me-3 fs-2">
                                            {{ $tier['icon'] }}
                                        </div>

                                        <div class="flex-grow-1 lh-1">
                                            <small class="text-muted text-uppercase" style="font-size: 0.65rem;">
                                                {{ $item->bagian }} (Lv. {{ $levelItem }})
                                            </small>


                                            <div class="fw-bold text-{{ $tier['warna'] }} fs-5">
                                                {{ $item->nama_outfit }} {{ $tier['nama'] }}
                                            </div>
                                        </div>


                                        <div class="text-end ps-2">
                                            @if($item->bonus_serangan > 0)
                                            {{-- Rumus Display: Base + (Level * 5) --}}
                                            <span class="badge bg-danger mb-1">
                                                ⚔️ {{ $item->bonus_serangan + ($levelItem * 5) }}
                                            </span><br>
                                            @endif

                                            @if($item->bonus_pertahanan > 0)
                                            <span class="badge bg-success">
                                                🛡️ {{ $item->bonus_pertahanan + ($levelItem * 5) }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="col-12">
                                    <p>Belum ada data outfit.</p>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    @else
                    <div class="alert alert-secondary text-center">
                        <p class="mb-0">Kamu belum memilih Suku. Silakan mainkan game untuk memilih suku!</p>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection