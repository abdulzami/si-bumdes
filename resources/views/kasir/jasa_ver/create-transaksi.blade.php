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
                    <li class="breadcrumb-item active"><a href="{{ route('transaksi-jasa') }}">Manajemen Transaksi</a></li>
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
                    <h3 class="card-title">Form Pilih Jasa<div class="badge badge-info"></div>
                    </h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{route('transaksi-jasa.cart')}}">
                        @csrf
                        <div class="form-group">
                            <label>Jasa</label>
                            <select class="form-control" name="id_jasa">
                            <option value="">Masukkan Jasa</option>
                            @foreach ($jasas as $index => $jasa)
                            <option value="{{Crypt::encryptString($jasa->id)}}">{{$jasa->name}}</option>
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
                                <th>Nama Jasa</th>
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
                                @if (session('cart-jasa'))
                                    @foreach (session('cart-jasa') as $id => $jasa)
                                    @php
                                    $i = Crypt::encryptString($id);
                                    $total_p = $jasa['harga_pelanggan'] * $jasa['jumlah'];
                                    $total_u = $jasa['harga_umum'] * $jasa['jumlah'];
                                    $semua_p += $total_p;
                                    $semua_u += $total_u;
                                    @endphp
                                        <tr>
                                            <td>{{$no ++}}.</td>
                                            <td>{{$jasa['nama']}}</td>
                                            <td>@currency2($jasa['harga_pelanggan'])</td>
                                            <td>@currency2($jasa['harga_umum'])</td>
                                            <td>@currency2($total_p)</td>
                                            <td>@currency2($total_u)</td>
                                            <td>{{$jasa['jumlah']}}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="4">
                                            <div class="row">
                                                <a href="{{route('transaksi-jasa.create.reset-keranjang',$i)}}"
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
                                            <a href="{{route('transaksi-jasa.bayar-umum')}}"
                                            class="btn btn-sm btn-light mb-3 border-dark float-right ml-1"><i class="fa fa-money-bill-wave">
                                            </i> Umum</a>
                                            <a href="{{route('transaksi-jasa.bayar-pelanggan')}}"
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
