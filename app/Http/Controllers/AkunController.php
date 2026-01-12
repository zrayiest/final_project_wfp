<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Akun;

class AkunController extends Controller
{
    public function updateEmas()
    {
        $user = Auth::user();
        $akun = Akun::where('user_id', $user->id)->first();
        $waktuUpdateEmasTerakhir = strtotime($akun->waktu_update_emas);
        //per 10 detik, bertambah 2 emas
        $selisihWaktu = time() - $waktuUpdateEmasTerakhir;
        $tambahEmas = ($selisihWaktu/10)*2;
        $akun->emas += $tambahEmas;
        $akun->waktu_update_emas = date('Y-m-d H:i:s');
        $akun->save();
        return response($akun->emas);
    }

    public function bangunBarak()
    {
        $user = Auth::user();
        $akun = Akun::where('user_id', $user->id)->first();
        $akun->waktu_bangun_barak = date('Y-m-d H:i:s');
        $akun->emas -= 100;
        $akun->save();
        return response('Barak terbangun!!!');
    }

    public function latihPasukan(Request $request) {
        $user = Auth::user();
        $akun = Akun::where('user_id', $user->id)->first();
        $akun->jumlah_pasukan_tersedia += $request->get('latih');
        $akun->emas -= $request->get('latih') * 5;
        $akun->save();
        return redirect('/home');
    }

    public function serangMusuh(Request $request) {
        $user = Auth::user();
        $penyerang = Akun::where('user_id', $user->id)->first();
        $terserang = Akun::where('nama', $request->get('musuh'))->first();
        //hitung jumlah nilai serang dari penyerang
        $nilaiSerang = $penyerang->jumlah_pasukan_tersedia * 5;
        $nilaiBertahan = $terserang->jumlah_pasukan_tersedia * 10;

        //jika menang
        if ($nilaiSerang > $nilaiBertahan) {
            $penyerang->emas += $terserang->emas;
            $terserang->emas = 0;
            $terserang->jumlah_pasukan_tersedia = 0;
            $penyerang->jumlah_pasukan_tersedia -= floor($nilaiBertahan/5);
        }
        //jika draw
        elseif ($nilaiSerang == $nilaiBertahan) {
            $penyerang->jumlah_pasukan_tersedia = 0;
            $terserang->jumlah_pasukan_tersedia = 0;
        }
        //jika kalah
        else {
            $penyerang->jumlah_pasukan_tersedia = 0;
            $terserang->jumlah_pasukan_tersedia -= floor($nilaiSerang)/10;
        }
        $penyerang->save();
        $terserang->save();
        return redirect('/home');
    }
}
