@extends('layouts.produkView')
@section('content')
<div class="container mt-4 shadow-lg p-3 mb-5 bg-white rounded border-0">
    <div class="card border-0">
        <div class="card-header bg-white">
            <h3>Ubah Data Produk</h3>
        </div>
        <div class="card-body">
            <form method="post" enctype="multipart/form-data" action="/produk/{{$produk->id}}">
                @method('PATCH')
                @csrf
                <div class="form-group row">
                    <label for="nama_produk" class="col-sm-2 col-form-label">Nama Produk</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control @error('nama_produk') is-invalid @enderror" id="nama_produk" name="nama_produk" value="{{$produk->nama}}">
                        @error('nama_produk')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="jenis_produk" class="col-sm-2 col-form-label">Kategori Produk</label>
                    <div class="col-sm-10">
                        <select class="custom-select @error('jenis_produk') is-invalid @enderror" id="jenis_produk" name="nama_kategori" value="{{old('jenis_produk')}}">
                            @foreach($kategoris as $data)
                            @if($data->id === $produk->id_kategori)
                            <option selected value="{{$data->id}}">{{$data->nama_kategori}}</option>
                            @else
                            <option value="{{$data->id}}">{{$data->nama_kategori}}</option>
                            @endif
                            @endforeach
                        </select>
                        @error('jenis_produk')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="stok" class="col-sm-2 col-form-label">Batas Produk</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control @error('stok') is-invalid @enderror" id="stok" name="stok" value="{{$produk->stok}}" min="1">
                        @error('stok')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="harga_jasa" class="col-sm-2 col-form-label">Harga Jasa (Rp.)</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control @error('harga_jasa') is-invalid @enderror" id="harga_jasa" name="harga_jasa" value="{{$produk->harga_jasa}}" min="1">
                        @error('harga_jasa')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="harga_produk" class="col-sm-2 col-form-label">Harga Produk (Rp.)</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control @error('harga_produk') is-invalid @enderror" id="harga_produk" name="harga_produk" value="{{$produk->harga_produk}}" min="1">
                        @error('harga_produk')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="berat" class="col-sm-2 col-form-label">Berat (Kg)</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control @error('berat') is-invalid @enderror" id="berat" name="berat" value="{{$produk->berat}}" min="1">
                        @error('berat')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="asal_negara" class="col-sm-2 col-form-label">Asal Negara</label>
                    <div class="col-sm-10">
                        <select class="custom-select @error('asal_negara') is-invalid @enderror" id="asal_negara" name="asal_negara">

                            <?PHP

                            $data = json_decode($negara, true);
                            foreach ($data as $item) {
                                if ($produk->asal_negara === $item) {
                                    echo "<option selected value='" . $item . "'>" . $item . "</option>";
                                } else {
                                    echo "<option value='" . $item . "'>" . $item . "</option>";
                                }
                            }

                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="keterangan" class="col-sm-2 col-form-label">Keterangan</label>
                    <div class="col-sm-10">
                        <div class="form-group">
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3"><?= $produk->keterangan; ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-12">
                        <a href="/edit-gambar/{{$produk->id}}" class="btn btn-success float-right">Edit Gambar</a>
                    </div>
                </div>
                <div class="form-group row">

                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Gambar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($gambars as $key => $data)
                            <tr>
                                <th scope="row">{{$key + 1}}</th>
                                <td><img class="rounded img-thumbnail" style="max-width: 150px;" src="{{asset('produk_images/' . $data->url)}}"></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>






                <div class="form-group row">
                    <div class="col-sm-10">
                        <input type="text" hidden name="id_user" value="{{Auth::user()->id}}">
                    </div>
                </div>
                <div class="form-group row pull-right d-inline p-2">
                    <div class="col-sm-10">
                        <a href="/produk" class="btn btn-primary">Kembali</a>
                    </div>
                </div>
                <div class="form-group row pull-right p-2">
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-success" style="background-color: #65587f; border: hidden">Ubah Data</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>
@stop