<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Akun;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //lakukan perhitungan emas
        $user = Auth::user();
        $akun = Akun::where('user_id', $user->id)->first();
        $waktuUpdateEmasTerakhir = strtotime($akun->waktu_update_emas);
        //per 10 detik, bertambah 2 emas
        $selisihWaktu = time() - $waktuUpdateEmasTerakhir;
        $tambahEmas = ($selisihWaktu/10)*2;
        $akun->emas += $tambahEmas;
        $akun->waktu_update_emas = date('Y-m-d H:i:s');
        $akun->save();

        $musuhs = Akun::getMusuhEligible();

        return view('home', ['akun' => $akun, 'musuhs' => $musuhs]);
    }
}
