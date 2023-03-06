@extends('layouts.master')
@push('custom-css')
<link rel="stylesheet" href={{ asset('assets/plugins/toastr/toastr.min.css') }}>
@endpush
@section('content-header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Tambah Transaksi</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('login') }}">Home</a></li>
                    <li class="breadcrumb-item active"><a href="{{ route('transaksi-produk') }}">Manajemen Transaksi</a></li>
                    <li class="breadcrumb-item active">Tambah Transaksi</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@endsection
@section('sb-transaksi')
    bg-light
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Pilih Produk<div class="badge badge-info"></div>
                    </h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{route('transaksi-produk.cart')}}">
                        @csrf
                        <div class="form-group">
                            <label>Produk</label>
                            <select class="form-control" name="id_produk">
                            <option value="">Masukkan Produk</option>
                            @foreach ($produks as $index => $produk)
                            <option value="{{Crypt::encryptString($produk->id)}}">{{$produk->nama}} ---- Stok : {{$produk->stok}}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="inputAddress">Jumlah</label>
                            <input type="number" class="form-control" name="jumlah" placeholder="Masukkan Jumlah">
                        </div>
                        <button type="submit" class="btn btn-success">Tambah e Keranjang</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Keranjang</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>                  
                              <tr>
                                <th style="width: 10px">No</th>
                                <th>Nama Produk</th>
                                <th>Harga Pelanggan</th>
                                <th>Harga Umum</th>
                                <th>Total Harga Pelanggan</th>
                                <th>Total Harga Umum</th>
                                <th style="width: 280px">Jumlah</th>
                              </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = 1; 
                                    $semua_p = 0;
                                    $semua_u = 0;
                                @endphp
                                @if (session('cart'))
                                    @foreach (session('cart') as $id => $produk)
                                    @php
                                    $i = Crypt::encryptString($id);
                                    $total_p = $produk['harga_pelanggan'] * $produk['jumlah'];
                                    $total_u = $produk['harga_umum'] * $produk['jumlah'];
                                    $semua_p += $total_p;
                                    $semua_u += $total_u;
                                    @endphp
                                        <tr>
                                            <td>{{$no ++}}.</td>
                                            <td>{{$produk['nama']}}</td>
                                            <td>@currency2($produk['harga_pelanggan'])</td>
                                            <td>@currency2($produk['harga_umum'])</td>
                                            <td>@currency2($total_p)</td>
                                            <td>@currency2($total_u)</td>
                                            <td>{{$produk['jumlah']}}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="4">
                                            <div class="row">
                                                <a href="{{route('transaksi-produk.create.reset-keranjang',$i)}}"
                                                class="btn btn-sm btn-warning mb-3"><i class="fa fa-undo">
                                                </i> Reset Keranjang</a>
                                        </td>
                                        <td>
                                            <b>@currency2($semua_p)</b>
                                        </td>
                                        <td>
                                            <b>@currency2($semua_u)</b>
                                        </td>
                                        <td>
                                            <b>Bayar :</b>
                                            <a href="{{route('transaksi-produk.bayar-umum')}}"
                                            class="btn btn-sm btn-light mb-3 border-dark float-right ml-1"><i class="fa fa-money-bill-wave">
                                            </i> Umum</a>
                                            <a href="{{route('transaksi-produk.bayar-pelanggan')}}"
                                            class="btn btn-sm btn-dark mb-3 float-right"><i class="fa fa-money-bill-wave">
                                            </i> Pelanggan</a>
                                        </td>
                                    </tr>
                                @else
                                <tr>
                                    <td colspan="7">
                                       <center>Keranjang Kosong</center>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                          </table> 
                    </div>
                    
                </div>
            </div>
        </div>
        {{-- <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Pemutakhiran Transaksi<div class="badge badge-info"></div>
                    </h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{route('pelanggan.store')}}">
                        @csrf
                        <div class="form-group">
                            <label for="inputAddress">Nama Transaksi</label>
                            <input type="text" class="form-control" name="nama_pelanggan" placeholder="Masukkan Nama Pelanggan">
                        </div>
                        <div class="form-group">
                            <label>Pelanggan</label>
                            <select class="form-control" name="kasir">
                            <option value="">Masukkan Pelanggan</option>
                            @foreach ($pelanggans as $index => $pelanggan)
                            <option value="{{Crypt::encryptString($pelanggan->id)}}">{{$pelanggan->nama}}</option>
                            @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </form>
                </div>
            </div>
        </div> --}}
    </div>
@endsection
@push('custom-script')
<script src={{ asset('assets/plugins/toastr/toastr.min.js') }}></script>
<script>
</script>
    @if (session('success'))
        <script>
            toastr.success('{{ session('success') }}');
        </script>
    @endif
    @if (session('gagal'))
        <script>
            toastr.error('{{ session('gagal') }}');

        </script>
    @endif
    @if ($errors->any())
    <script>
        let errornya = [
        @foreach ($errors->all() as $error)
            [ "{{ $error }}" ], 
        @endforeach
        ];
        errornya.forEach(function(error){
            toastr.warning(error);
        });
    </script>
    @endif
@endpush
