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
                    <div id="area-bangunan">
                    @if (!$akun->waktu_bangun_barak)
                        <a href="{{ url('/bangun-barak') }}" target-id="area-bangunan" class="proses btn btn-info">[Bangun Barak - 100 emas]</a>
                    @else 
                        <a href="#" class="proses btn btn-success" data-bs-toggle="modal" data-bs-target="#barakModal">[Latih Pasukan 5 emas / orang]</a>
                    @endif
                        <div id="stok-pasukan">
                            @if ($akun->jumlah_pasukan_tersedia)
                                Jumlah Pasukan: {{ number_format($akun->jumlah_pasukan_tersedia, 0, ',', '.') }} orang
                                <br/><a href="#" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#musuhModal">SERANGGGGG!!!</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="barakModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Latih Pasukan</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="{{ url('/latih-pasukan') }}" id="form-latih-pasukan" target-id="stok-pasukan">
            @csrf
            <div class="row">
                <div class="col-sm-4">
                    Jumlah
                </div>
                <div class="col-sm-4">
                    <input type="text" name="latih" class="form-control" style="text-align: right" placeholder="Maks {{ number_format(floor($akun->emas/5), 0, ',', '.') }}"/>
                </div>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-primary" id="tombol-latih">Latih</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="musuhModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Pilih Musuh</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="{{ url('/serang-musuh') }}" id="form-serang-musuh">
            @csrf
            <div class="row">
                <div class="col-sm-4">
                    Daftar Musuh
                </div>
                <div class="col-sm-8">
                    <select class="form-control" name="musuh">
                        <option>-- Pilih Musuh Anda --</option>
                        @foreach ($musuhs as $musuh)
                        <option value="{{ $musuh['nama'] }}">
                            {{ $musuh['nama'].' - '.$musuh['suku'] }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-danger" id="tombol-serang">SERANGGGG!!!</button>
      </div>
    </div>
  </div>
</div>
@endsection
@section('profil-akun')
Emas: <strong id="jumlah-emas">{{ number_format($akun->emas, 0, ',', '.') }}</strong> - Suku: {{ $akun->suku->nama }}
@endsection

<script>
setInterval(myTimer, 5000);

function myTimer() {
  $.ajax({
        url: "<?php echo url('/update-emas') ?>",
        success: function( result ) {
            const formatResult = new Intl.NumberFormat().format(result);
            $( "#jumlah-emas" ).html(formatResult);
        }
    });
}
</script>
