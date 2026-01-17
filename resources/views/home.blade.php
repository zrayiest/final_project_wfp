@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif
                    @if (session('success'))
                    <div class="alert alert-success fw-bold" role="alert">
                        {{ session('success') }}
                    </div>
                    @endif
                    @if (session('error'))
                    <div class="alert alert-danger fw-bold" role="alert">
                        {{ session('error') }}
                    </div>
                    @endif

                    <div id="area-bangunan">
                        @if (!$akun->waktu_bangun_barak)
                        <a href="{{ url('/bangun-barak') }}" class="btn btn-info">[Bangun Barak - 100 emas]</a>
                        @else
                        <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#barakModal">[Latih Pasukan 5 emas / orang]</a>
                        @endif

                        <div id="stok-pasukan" class="mt-3">
                            @if ($akun->jumlah_pasukan_tersedia)
                            <h5>Pasukan: {{ number_format($akun->jumlah_pasukan_tersedia, 0, ',', '.') }} orang</h5>
                            <button class="btn btn-danger mt-2" data-bs-toggle="modal" data-bs-target="#musuhModal">⚔️ SERANGGGGG!!!</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="barakModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Latih Pasukan</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="POST" action="{{ url('/latih-pasukan') }}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-4">Jumlah</div>
                        <div class="col-sm-8">
                            <input type="number" name="latih" class="form-control" style="text-align: right" placeholder="Maks {{ number_format(floor($akun->emas/5), 0, ',', '.') }}" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Latih</button>
                </div>
            </form>

        </div>
    </div>
</div>

<div class="modal fade" id="musuhModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h1 class="modal-title fs-5">Pilih Lawan</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body bg-light">
                <div class="d-grid gap-2">
                    @forelse ($musuhs as $musuh)
                    @php $cek = Auth::user()->akun->cekPrediksiLawan($musuh); @endphp

                    <div class="card border-{{ $cek['warna'] }} mb-1 shadow-sm" style="border-left: 5px solid;">
                        <div class="card-body p-2 d-flex justify-content-between align-items-center">

                            <div>
                                <strong>{{ $musuh->nama }}</strong>
                                <span class="badge bg-secondary" style="font-size:0.7em">{{ $musuh->suku->nama }}</span>
                                <div style="font-size: 11px;" class="text-muted">
                                    Lv.{{ $musuh->level }} | ⚔️ {{ $musuh->jumlah_pasukan_tersedia }}
                                </div>
                                <small class="text-{{ $cek['warna'] }} fw-bold" style="font-size: 10px;">
                                    {{ $cek['pesan'] }}
                                </small>
                            </div>

                            <form action="{{ url('/serang-musuh/' . $musuh->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger fw-bold shadow-sm">
                                    SERANG
                                </button>
                            </form>
                        </div>
                    </div>

                    @empty
                    <div class="text-center py-5 text-muted">
                        <p>Tidak ada musuh ditemukan.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('profil-akun')
Emas: <strong id="jumlah-emas" class="text-warning">{{ number_format($akun->emas, 0, ',', '.') }}</strong> |
Suku: {{ $akun->suku->nama }}
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    setInterval(myTimer, 5000);

    function myTimer() {
        $.ajax({
            url: "{{ url('/update-emas') }}", // Pakai Blade helper lebih rapi
            success: function(result) {
                const formatResult = new Intl.NumberFormat('id-ID').format(result);
                $("#jumlah-emas").html(formatResult);
            }
        });
    }
</script>