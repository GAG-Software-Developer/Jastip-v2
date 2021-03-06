<?php

namespace App\Console\Commands;

use App\PenjualanPreorder;
use App\ProdukBulkBuy;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;


class updateStatusBulkbuy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bulkbuy:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will update the produk status according to bulkbuy time limit';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $produksBulkbuy = ProdukBulkBuy::get();
        // Update status batas waktu bulk buys
        foreach($produksBulkbuy as $data) {
            $batas_waktu = $data->batas_waktu;
            $waktu_sekarang = Carbon::now()->format('Y-m-d H:i:s');
            $jumlah_target = $data->jumlah_target;
            if($batas_waktu < $waktu_sekarang && $jumlah_target > 0) {
                ProdukBulkBuy::where('id', $data->id)
                    ->update([
                        'status_bulk' => 2
                    ]);
                $penjualan_preorders = PenjualanPreorder::where('id_bulkbuy', $data->id)->get();
                foreach($penjualan_preorders as $po) {
                    if($po->status_order === 'menunggu') {
                        $saldo_lama = User::where('id', $po->id_user)->select('users.saldo')->get()[0]->saldo;
                        $saldo_baru = $saldo_lama + $po->total_harga;
                        User::where('id', $po->id_user)
                            ->update([
                                'saldo' => $saldo_baru
                            ]);
                        PenjualanPreorder::where('id', $po->id)
                            ->update([
                                'status_order' => 5
                            ]);
                    }
                }
            }
        }
        //update status pengiriman bulk buys

        //PENGECEKAN DICEK LAGI YAAA
        $penjualan_preorder_bulkbuys = PenjualanPreorder::where('id_bulkbuy', '!=', 'Null')
                                ->join('produk_bulk_buys', 'produk_bulk_buys.id', 'penjualan_preorders.id_bulkbuy')->get();
        foreach ($penjualan_preorder_bulkbuys as $data) {
            $waktu_sekarang = strtotime(Carbon::now()->format('Y-m-d H:i:s'));
            $batas_waktu = strtotime($data->batas_waktu);
            $sisa_waktu = intval(($batas_waktu - $waktu_sekarang) / 60 / 60 / 24); //Mengasilkan Hari
            if($sisa_waktu < 0 && $data->status_order === 'menunggu') {
                $saldo_lama = User::where('id', $data->id_user)->select('users.saldo')->get()[0]->saldo;
                $saldo_baru = $saldo_lama + $data->total_harga;
                User::where('id', $data->id_user)
                    ->update([
                        'saldo' => $saldo_baru
                    ]);
                PenjualanPreorder::where('id', $data->id)
                    ->update([
                        'status_order' => 5
                    ]);
            }
        }

    }
}
