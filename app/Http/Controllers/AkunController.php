<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Akun;
use App\Models\User;

class AkunController extends Controller
{
    public function updateEmas()
    {
        $user = Auth::user();
        $akun = Akun::where('user_id', $user->id)->first();
        $waktuUpdateEmasTerakhir = strtotime($akun->waktu_update_emas);
        //per 10 detik, bertambah 2 emas
        $selisihWaktu = time() - $waktuUpdateEmasTerakhir;
        $tambahEmas = ($selisihWaktu / 10) * 2;
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

    public function latihPasukan(Request $request)
    {
        $user = Auth::user();
        $akun = Akun::where('user_id', $user->id)->first();
        $akun->jumlah_pasukan_tersedia += $request->get('latih');
        $akun->emas -= $request->get('latih') * 5;
        $akun->save();
        return redirect('/home');
    }

    public function serangMusuh($id)
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $penyerang = $user->akun;
        $musuh = \App\Models\Akun::find($id);

        if (!$penyerang || !$musuh) return back()->with('error', 'Data error.');
        if ($penyerang->id == $musuh->id) return back()->with('error', 'Tidak bisa menyerang diri sendiri.');
        if ($penyerang->jumlah_pasukan_tersedia < 10) return back()->with('error', 'Pasukan kurang dari 10!');

        $tipe = 'melee';
        $baseAtk = $penyerang->suku->serang_melee;

        if ($penyerang->suku->nama == 'Marksman') {
            $tipe = 'range';
            $baseAtk = $penyerang->suku->serang_range;
        } elseif ($penyerang->suku->nama == 'Mage') {
            $tipe = 'magic';
            $baseAtk = $penyerang->suku->serang_magic;
        }

        $heroAtk  = $baseAtk + ($penyerang->level * 2) + ($penyerang->level_senjata * 5);
        $totalAtk = $heroAtk * $penyerang->jumlah_pasukan_tersedia;

        $baseDef = 0;
        if ($tipe == 'range') $baseDef = $musuh->suku->tahan_range;
        elseif ($tipe == 'magic') $baseDef = $musuh->suku->tahan_magic;
        else $baseDef = $musuh->suku->tahan_melee;

        $heroDef  = $baseDef + ($musuh->level * 2) + ($musuh->level_armor * 5);
        $totalDef = $heroDef * $musuh->jumlah_pasukan_tersedia;

        // 5. PENENTUAN PEMENANG
        $selisih = $totalAtk - $totalDef;
        $jarahan = 0;
        $korbanPenyerang = 0;
        $korbanMusuh = 0;
        $pemenangId = 0;
        $pesan = "";
        $alert = "";

        if ($selisih > 0) {
            // --- MENANG ---
            $alert = 'success'; // Warna Hijau
            $pemenangId = $penyerang->id;

            $korbanPenyerang = intval($penyerang->jumlah_pasukan_tersedia * 0.10); // Rugi 10%
            $korbanMusuh     = intval($musuh->jumlah_pasukan_tersedia * 0.50);     // Rugi 50%
            $jarahan         = intval($musuh->emas * 0.90);                        // Rampok 90%

            $penyerang->emas += $jarahan;
            $musuh->emas -= $jarahan;

            $pesan = "MENANG! Musuh hancur lebur. Kamu merampok $jarahan Emas.";
        } else {
            // --- KALAH ---
            $alert = 'error'; // Warna Merah
            $pemenangId = $musuh->id;

            $korbanPenyerang = intval($penyerang->jumlah_pasukan_tersedia * 0.40); // Rugi 40%
            $korbanMusuh     = intval($musuh->jumlah_pasukan_tersedia * 0.05);     // Rugi 5%

            $pesan = "KALAH! Pertahanan musuh terlalu kuat. Pasukanmu mundur.";
        }


        $penyerang->jumlah_pasukan_tersedia -= $korbanPenyerang;
        $musuh->jumlah_pasukan_tersedia -= $korbanMusuh;

        // Cegah minus
        if ($penyerang->jumlah_pasukan_tersedia < 0) $penyerang->jumlah_pasukan_tersedia = 0;
        if ($musuh->jumlah_pasukan_tersedia < 0) $musuh->jumlah_pasukan_tersedia = 0;

        $penyerang->save();
        $musuh->save();


        \App\Models\LaporanPerang::create([
            'penyerang_id' => $penyerang->id,
            'musuh_id'     => $musuh->id,
            'pemenang_id'  => $pemenangId,
            'emas_jarahan' => $jarahan,
            'pasukan_mati_penyerang' => $korbanPenyerang,
            'pasukan_mati_musuh'     => $korbanMusuh
        ]);

        // KEMBALI KE HOME DENGAN PESAN
        return back()->with($alert, $pesan);
    }

    public function laporan()
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        $laporans = \App\Models\LaporanPerang::where('penyerang_id', $user->akun->id)
            ->orWhere('musuh_id', $user->akun->id)
            ->latest()
            ->get();

        return view('laporan', compact('laporans', 'user'));
    }

    public function profile()
    {
        $user = User::with('akun.suku.outfits')->find(Auth::id());
        if (!$user) {
            return redirect('/login');
        }
        return view('profile', compact('user'));
    }

    public function trainingCamp()
    {
        $user = Auth::user();
        if (!$user->akun) {
            return redirect('/welcome');
        }
        $biayaUpgrade = $user->akun->level * 100;

        return view('training_camp', compact('user', 'biayaUpgrade'));
    }

    public function prosesUpgrade()
    {
        $user = Auth::user();
        $akun = $user->akun;

        if ($akun->level >= 100) {
            return redirect()->back()->with('error', 'Maksimal Level 100 tercapai!');
        }

        $biaya = $akun->level * 100;

        if ($akun->emas < $biaya) {
            return redirect()->back()->with('error', 'Emas tidak cukup! Butuh ' . number_format($biaya));
        }

        $akun->emas -= $biaya;
        $akun->level += 1;
        $akun->save();

        return redirect()->back()->with('success', 'Level UP! Sekarang Level ' . $akun->level);
    }

    public function blacksmith()
    {
        $user = Auth::user();
        if (!$user->akun) return redirect('/welcome');

        $akun = $user->akun;

        $lvlSenjata = $akun->level_senjata;
        $senjata = [
            'level'      => $lvlSenjata,
            'tier'       => $akun->getTierInfo($lvlSenjata),
            'bonus_skrg' => $lvlSenjata * 2,
            'bonus_next' => ($lvlSenjata + 1) * 2,
            'biaya'      => ($lvlSenjata + 1) * 500
        ];

        $lvlArmor = $akun->level_armor;
        $armor = [
            'level'      => $lvlArmor,
            'tier'       => $akun->getTierInfo($lvlArmor),
            'bonus_skrg' => $lvlArmor * 2,
            'bonus_next' => ($lvlArmor + 1) * 2,
            'biaya'      => ($lvlArmor + 1) * 500
        ];

        return view('blacksmith', compact('user', 'senjata', 'armor'));
    }

    public function upgradeItem(\Illuminate\Http\Request $request)
    {
        $user = \Illuminate\Support\Facades\Auth::user();


        if (!$user->akun) {
            return redirect('/home');
        }

        $akun = $user->akun;
        $tipe = $request->tipe;


        if ($tipe == 'senjata') {

            if ($akun->level_senjata >= 100) {
                return back()->with('error', 'Senjata sudah mencapai Level Maksimal!');
            }


            $biaya = ($akun->level_senjata + 1) * 500;


            if ($akun->emas >= $biaya) {
                $akun->emas -= $biaya;
                $akun->level_senjata += 1;
                $akun->save();

                return back()->with('success', 'Berhasil! Senjatamu semakin tajam! ⚔️');
            } else {
                return back()->with('error', 'Emas tidak cukup! Kamu butuh ' . number_format($biaya));
            }
        } elseif ($tipe == 'armor') {

            if ($akun->level_armor >= 100) {
                return back()->with('error', 'Armor sudah mencapai Level Maksimal!');
            }


            $biaya = ($akun->level_armor + 1) * 500;

            if ($akun->emas >= $biaya) {
                $akun->emas -= $biaya;
                $akun->level_armor += 1;
                $akun->save();

                return back()->with('success', 'Berhasil! Pertahananmu semakin kuat! 🛡️');
            } else {
                return back()->with('error', 'Emas tidak cukup! Kamu butuh ' . number_format($biaya));
            }
        }

        return back()->with('error', 'Terjadi kesalahan sistem.');
    }
}
