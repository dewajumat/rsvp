<?php

namespace App\Http\Controllers;

use App\Models\Penghuni;
use App\Models\Pemeliharaan;
use App\Models\Laporan;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index()
    {
        $total_data = Penghuni::all()->count();
        $data_penghuni = Penghuni::orderBy("no_pintu","asc")->get();
        return view('vkontrakan/index',compact(['data_penghuni', 'total_data']));
    }
    
    public function create()
    {
        $nopintu = Penghuni::select('no_pintu')->get();
        $nopintu_arr = array();
        foreach ($nopintu as $np) {
            array_push($nopintu_arr,$np->no_pintu);
        }
        return view('vkontrakan/create',compact(['nopintu_arr']));
    }
    
    public function edit($no_pintu)
    {
        $nopintu = Penghuni::select('no_pintu')->get();
        $nopintu_arr = array();

        foreach ($nopintu as $np) {
            array_push($nopintu_arr,$np->no_pintu);
        }

        $primary_key_penghuni = Penghuni::find($no_pintu);
        return view('vkontrakan/edit', compact(['primary_key_penghuni', 'nopintu_arr']));
    }
    
    public function store(Request $request)
    {
        $tgl = date("Y-m-d");
        $ket = "Pembayaran nomor pintu ".$request->input('no_pintu');
        $terbayar = $request->input('terbayar');

        $report_var['tgl']=(string)$tgl;
        $report_var['ket']=(string)$ket;
        $report_var['terbayar']=(string)$terbayar;

        Penghuni::create($request->except(['_token']));
        Laporan::create($report_var);

        return redirect('vkontrakan/index');
    }
    
    public function update($no_pintu, Request $request)
    {
        $primary_key_penghuni = Penghuni::find($no_pintu);
        $terbayar = $primary_key_penghuni->terbayar;
        $tgl = date("Y-m-d");
        $ket = "Pembayaran nomor pintu ".$request->input('no_pintu');
        $inp_terbayar = $request->input('terbayar')-$terbayar;

        $report_var['tgl']=(string)$tgl;
        $report_var['ket']=(string)$ket;
        $report_var['terbayar']=(string)$inp_terbayar;

        Laporan::create($report_var);
        $primary_key_penghuni->update($request->except(['_token']));

        return redirect('vkontrakan/index');
    }

    public function destroy($no_pintu)
    {
        $primary_key_penghuni = Penghuni::find($no_pintu);
        $primary_key_penghuni->delete();

        return redirect('vkontrakan/index');
    }

    public function mindex()
    {
        $total_pemeliharaan = 0;
        $data_pemeliharaan = Pemeliharaan::all();
        $bulan=date("Y-m");
        $pemeliharaan_arr= array();

        // Data Pemeliharaan
        foreach ($data_pemeliharaan as $vm) {
            $bulandb = date("Y-m",strtotime($vm->tgl));
            if ($bulan==$bulandb) {
                array_push($pemeliharaan_arr,$vm);
            }
        }
        //-----/-----//

        // Total Pemeliharaan (Pengeluaran)
        foreach ($pemeliharaan_arr as $mt) {
            $total_pemeliharaan = $total_pemeliharaan+$mt->total_hrg;  
        }
        //-----/-----//
        return view('vkontrakan/mindex', compact(['pemeliharaan_arr', 'total_pemeliharaan']));
    }

    public function report()
    {
        $data_pemeliharaan = Pemeliharaan::all();
        $total_pemeliharaan = 0;
        $total_pembayaran = 0;
        $data_laporan = Laporan::all();
        $bulan=date("Y-m");
        $laporan_arr= array();
        $pemeliharaan_arr = array();

        // Data Laporan
        foreach ($data_laporan as $dl) {
            $bulandb = date("Y-m",strtotime($dl->tgl));
            if ($bulan==$bulandb) {
                array_push($laporan_arr,$dl);
            }
        }
        //-----/-----//

        // Total Pemeliharaan (Pengeluaran)
        foreach ($data_pemeliharaan as $dl) {
            $bulandb = date("Y-m",strtotime($dl->tgl));
            if ($bulan==$bulandb) {
                array_push($pemeliharaan_arr,$dl);
            }                                       
        }
        foreach ($pemeliharaan_arr as $pa) {
            $total_pemeliharaan = $total_pemeliharaan+$pa->total_hrg;  
        }                                           
        //-----/-----//

        // Total Pembayaran (Pemasukan)
        foreach ($laporan_arr as $la) {
            $total_pembayaran = $total_pembayaran+$la->terbayar;  
        }                                           
        //-----/-----//

        // Total Keuangan
        if (count($laporan_arr)!==0){
            $total_keuangan = $total_pembayaran-$total_pemeliharaan;
        }
        else{
            $total_keuangan=0;
        }
        //-----/-----//
        return view('vkontrakan/report',compact(['laporan_arr', 'total_pemeliharaan', 'total_keuangan', 'total_pembayaran']));
    }

    public function mcreate()
    {  
        $data_pemeliharaan = Pemeliharaan::all();
        return view('vkontrakan/mcreate');
    }

    public function mstore(Request $request)
    { 
        $req_inp = $request->except(['_token']);
        $harga = $request->input('hrg_brg');
        $jmlh_brg = $request->input('jmlh_brg');
        $tot = (int)$harga*(int)$jmlh_brg;
        $req_inp['total_hrg']=(string)$tot;

        Pemeliharaan::create($req_inp);   

        return redirect('vkontrakan/mindex');
    }

    public function landpage()
    {
        return view('vkontrakan/landpage');
    }
}