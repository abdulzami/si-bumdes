@extends('layouts.master')
@push('custom-css')
<link rel="stylesheet" href={{ asset('assets/plugins/toastr/toastr.min.css') }}>
@endpush
@section('content-header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Bayar Pelanggan</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('login') }}">Home</a></li>
                    <li class="breadcrumb-item active"><a href="{{ route('transaksi-jasa') }}">Manajemen Transaksi</a></li>
                    <li class="breadcrumb-item active"><a href="{{ route('transaksi-jasa.create') }}">Tambah Transaksi</a></li>
                    <li class="breadcrumb-item active">Bayar Pelanggan</li>
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
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Jasa</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>                  
                              <tr>
                                <th style="width: 10px">No</th>
                                <th>Nama Jasa</th>
                                <th>Harga</th>
                                <th>Total</th>
                                <th style="width: 280px">Jumlah</th>
                              </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = 1; 
                                    $semua_p = 0;
                                @endphp
                                @if (session('cart-jasa'))
                                    @foreach (session('cart-jasa') as $id => $jasa)
                                    @php
                                    $i = Crypt::encryptString($id);
                                    $total_p = $jasa['harga_pelanggan'] * $jasa['jumlah'];
                                    $semua_p += $total_p;
                                    @endphp
                                        <tr>
                                            <td>{{$no ++}}.</td>
                                            <td>{{$jasa['nama']}}</td>
                                            <td>@currency2($jasa['harga_pelanggan'])</td>
                                            <td>@currency2($total_p)</td>
                                            <td>{{$jasa['jumlah']}}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="5">
                                            <b>Total Biaya : @currency2($semua_p)</b>
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
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Bayar<div class="badge badge-info"></div>
                    </h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{route('transaksi-jasa.store-pelanggan')}}">
                        @csrf
                        <div class="form-group">
                            <label for="inputAddress">Nama Transaksi</label>
                            <input type="text" class="form-control" name="nama_transaksi" placeholder="Masukkan Nama Transaksi">
                        </div>
                        <div class="form-group">
                            <label>Pelanggan</label>
                            <select class="form-control" name="pelanggan">
                                <option value="">Masukkan Pelanggan</option>
                                @foreach ($pelanggans as $index => $pelanggan)
                                <option value="{{Crypt::encryptString($pelanggan->id)}}">{{$pelanggan->nama}}</option>
                                @endforeach
                                </select>
                        </div>
                        <div class="form-group">
                            <label for="inputAddress">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" placeholder="Masukkan Penyusustan Harga per Tahun" aria-label="Username" aria-describedby="basic-addon1">
                        </div>
                        <div class="form-group">
                            <label for="inputAddress">Bayar</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">Rp</span>
                                <input type="text" class="form-control" id="masking2" name="bayar" placeholder="Masukkan Bayar">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('custom-script')
<script src={{ asset('assets/plugins/toastr/toastr.min.js') }}></script>
<script src={{ asset('assets/plugins/mask/jquery.mask.min.js') }}></script>
<script>
$(document).ready(function(){
    $('#masking2').mask('#.##0', {reverse: true});
})
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
