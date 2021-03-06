@extends('layouts.produkview')
@section('content')
<div class="container mt-5">
    <div class="card  shadow-lg p-3 mb-5 bg-white rounded border-0">
        <div class="card-header bg-white">
            <h3>Daftar Penjualan Bulk-Buy</h3>
        </div>
        <div class="card-body">
            @if (session('status') === "Data Berhasil Ditambahkan!")
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
            @elseif (session('status')==="Data Berhasil Diubah!")
            <div class="alert alert-primary">
                {{ session('status') }}
            </div>
            @elseif (session('status')==="Data Berhasil Dibatalkan!")
            <div class="alert alert-danger">
                {{ session('status') }}
            </div>
            @endif
            <div class="mt-3">
                <table id="table_order" class="table  table-responsive table-hover">
                    <thead>
                        <tr>
                            <th>Kode Transaksi</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Asal Negara</th>
                            <th>Jumlah Beli</th>
                            <th>Kurir</th>
                            <th>Servis</th>
                            <th>Total Harga</th>
                            <th>Batas Pengiriman</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($penjualanOrders as $data)
                        <tr>
                            <td>{{$data->kode_transaksi}}</td>
                            <td>{{$data->nama}}</td>
                            <td>{{$data->nama_kategori}}</td>
                            <td>{{$data->asal_negara}}</td>
                            <td>{{$data->kuantitas}}</td>
                            <td>{{$data->kurir}}</td>
                            <td>{{$data->service}}</td>
                            <td>{{$data->total_harga}}</td>
                            <td>
                                @if($data->status_order === 'menunggu')
                                {{$sisa_waktu}} Hari
                                @elseif($data->status_order === 'dikirim')
                                Sedang dikirim
                                @else
                                @endif
                            </td>
                            <td>{{$data->status_order}}</td>
                            <td>
                                @if($data->status_order === 'dikirim')
                                <a href="/penjualan-bulk/{{$data->id}}" class="badge badge-warning">Ganti Resi</a>
                                @elseif($data->status_order ==='menunggu' && $data->jumlah_target === 0)
                                <a href="/penjualan-bulk/{{$data->id}}" class="badge badge-success">Kirim Barang</a>
                                <form action="/penjualan-bulk/{{$data->id}}" method="post">
                                    @method('delete')
                                    @csrf
                                    <button type="submit" class="badge badge-danger border-0">Cancel Order</button>
                                </form>
                                @elseif($data->status_order ==='menunggu')
                                <label>Menunggu Target Terpenuhi</label>
                                <form action="/penjualan-bulk/{{$data->id}}" method="post">
                                    @method('delete')
                                    @csrf
                                    <button type="submit" class="badge badge-danger border-0">Cancel Order</button>
                                </form>
                                @elseif($data->status_order ==='diterima')
                                <label>Selesai</label>
                                @elseif($data->status_order ==='dibatalkan')
                                <label>Dibatalkan</label>
                                @endif

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop