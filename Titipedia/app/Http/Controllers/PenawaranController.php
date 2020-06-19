<?php

namespace App\Http\Controllers;

use App\Penawaran;
use App\PenjualanRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Req;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;


class PenawaranController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Req $request)
    {
        $penawarans = DB::table('penawarans')
            ->join('users', 'users.id', 'penawarans.id_penawar')
            ->where('id_request', $request->id)
            ->select('penawarans.*', 'users.name as name')
            ->get();
        $gambars = DB::table('gambars')
            ->where('id_request', $request->id)
            ->get();
        $users = DB::table('users')
            ->where('id', $request->id_user)
            ->get();
        $kategoris = DB::table('kategoris')
            ->where('id', $request->id_kategori)
            ->get();
        $user = $users[0]->name;
        $kategori = $kategoris[0]->nama_kategori;
        $isAjukanPenawaran = false;
        foreach ($penawarans as $item) {
            if ($item->id_penawar === Auth::user()->id) {
                $isAjukanPenawaran = true;
            }
        }
        return view('pages.penawaran.penawaran', compact('penawarans', 'request', 'gambars', 'user', 'kategori', 'isAjukanPenawaran'));
    }
    public function indexCekPenawaran(Req $request)
    {
        $penawarans = DB::table('penawarans')
            ->join('users', 'users.id', 'penawarans.id_penawar')
            ->where('id_request', $request->id)
            ->select('penawarans.*', 'users.name as name')
            ->get();
        $gambars = DB::table('gambars')
            ->where('id_request', $request->id)
            ->get();
        $users = DB::table('users')
            ->where('id', $request->id_user)
            ->get();
        $kategoris = DB::table('kategoris')
            ->where('id', $request->id_kategori)
            ->get();
        $user = $users[0]->name;
        $kategori = $kategoris[0]->nama_kategori;
        $isAjukanPenawaran = false;

        return view('pages.request.cek-penawaran', compact('penawarans', 'request', 'gambars', 'user', 'kategori'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Req $request)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://api.rajaongkir.com/starter/city",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "key: 20abcef3dbf0bc2149a7412bc9b60005"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        return view('pages.penawaran.create', compact('request', 'response'));
    }
    public function pilihPenawaran(Penawaran $penawaran)
    {
        //dd($penawaran->harga_jasa_penawaran);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://api.rajaongkir.com/starter/city",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "key: 20abcef3dbf0bc2149a7412bc9b60005"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $penawar = DB::table('users')
            ->where('id', $penawaran->id_penawar)
            ->get();
        $namaPenawar = $penawar[0];
        return view('pages.request.pilih-penawaran', compact('penawaran', 'response', 'namaPenawar'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'harga_produk' => 'required',
            'harga_jasa' => 'required',
            'alamat_penawar' => 'required',
            'kota_penawar' => 'required',
        ]);
        Penawaran::create([
            'id_request' => $request->id_request,
            'id_penawar' => $request->id_user,
            'harga_produk_penawaran' => $request->harga_produk,
            'harga_jasa_penawaran' => $request->harga_jasa,
            'alamat_penawaran' => $request->alamat_penawar,
            'kota_penawaran' => $request->kota_penawar,
            'status' => 1
        ]);
        return redirect('/penawaran/' . $request->id_request)->with('status', 'Data Berhasil Ditambahkan!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Penawaran  $penawaran
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $penjualan_request = DB::table('penawarans')
            ->join('penjualan_requests', 'penjualan_requests.id_penawaran', 'penawarans.id')
            ->join('requests', 'requests.id', 'penawarans.id_request')
            ->join('users', 'users.id', 'requests.id_user')
            ->where('penawarans.id_penawar', Auth::user()->id)
            ->select('users.name', 'users.no_hp', 'penjualan_requests.*', 'requests.nama_req as nama_req', 'requests.jumlah_req as jumlah_req', 'requests.kota_req as kota_req', 'requests.alamat_req as alamat_req', 'requests.keterangan as keterangan')
            ->get();
        //-------------FIX-------------
        $sisa_waktu = 0;
        if (count($penjualan_request) > 0) {
            $waktu_sekarang = strtotime(Carbon::now()->format('Y-m-d H:i:s'));
            $waktu_pembelian = strtotime(date('Y-m-d', strtotime($penjualan_request[0]->created_at. ' + 3 days')));
            $sisa_waktu = strval(intval(($waktu_pembelian - $waktu_sekarang) / 60 / 60 / 24)); //Mengasilkan Hari
        }
        return view('pages.penawaran.penjualan_penawaran', compact('penjualan_request', 'sisa_waktu'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Penawaran  $penawaran
     * @return \Illuminate\Http\Response
     */
    public function edit(Penawaran $penawaran)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Penawaran  $penawaran
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Penawaran $penawaran)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Penawaran  $penawaran
     * @return \Illuminate\Http\Response
     */
    public function destroy(Penawaran $penawaran)
    {
        Penawaran::destroy($penawaran->id);
        return redirect()->back()->with('status', 'Data Penawaran Berhasil Dihapus!');
    }
}
