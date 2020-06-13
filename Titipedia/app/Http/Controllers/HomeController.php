<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //Jika ingin tidak melewati login untuk pergi ke halaman ini, hapus code d bawah
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //auth::chek untuk kondisi jika user login
        //if (Auth::check()) {
        $kategoris = DB::table('kategoris')->get();

        $produks = DB::table('produks')
            ->join('users', 'users.id', '=', 'produks.id_user')
            ->join('kategoris', 'produks.id_kategori', '=', 'kategoris.id')
            ->join('gambars', 'produks.id', '=', 'gambars.id_produk')->groupBy('produks.id')
            ->select('produks.*', 'users.name', 'gambars.url', 'kategoris.nama_kategori')
            ->latest()->take(8)->get();

        $bulkbuy = DB::table('produk_bulk_buys')
            ->join('users', 'users.id', '=', 'produk_bulk_buys.id_user')
            ->join('kategoris', 'produk_bulk_buys.id_kategori', '=', 'kategoris.id')
            ->join('gambars', 'produk_bulk_buys.id', '=', 'gambars.id_bulkbuy')->groupBy('produk_bulk_buys.id')
            ->select('produk_bulk_buys.*', 'users.name', 'gambars.url', 'kategoris.nama_kategori')
            ->latest()->take(8)->get();
        //$produk = DB::table('produks')->where('id_user', 1)->count();
        //ambil notif pesan

        //dd($produk);
        return view('pages.home', compact('produks', 'kategoris', 'bulkbuy'));
    }
}
