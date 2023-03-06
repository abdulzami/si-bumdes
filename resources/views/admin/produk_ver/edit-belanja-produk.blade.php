@extends('layouts.master')
@push('custom-css')
<link rel="stylesheet" href={{ asset('assets/plugins/toastr/toastr.min.css') }}>
@endpush
@section('content-header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Edit Belanja Produk<span class="badge badge-info"></span></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('login') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('belanja-produk') }}">Pengeluaran Belanja Produk</a></li>
                    <li class="breadcrumb-item active">Edit Belanja Produk</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@endsection
@section('sb-open-pengeluaran')
    menu-open
@endsection
@section('sb-block-pengeluaran')
    style="display: block"
@endsection
@section('sb-belanja_produk')
    bg-light
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Edit Belanja Produk<div class="badge badge-info"></div>
                    </h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{route('belanja-produk.update',$id)}}">
                        @csrf
                        @method('put')
                        <div class="form-group">
                            <label for="inputAddress">Nama Produk</label>
                            <input type="text" class="form-control" value="{{$belanjas->nama}}" name="nama_produk" placeholder="Masukkan Nama Produk">
                        </div>
                        <div class="form-group">
                            <label for="inputAddress">Kode Produk</label>
                            <input type="text" class="form-control"  value="{{$belanjas->kode_produk}}" name="kode_produk" placeholder="Masukkan Kode Produk">
                        </div>
                        <div class="form-group">
                            <label for="inputAddress">Harga Beli Satuan</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">Rp</span>
                                <input type="text" id="masking1" name="harga_beli_satuan"  value="{{$belanjas->harga_beli_satuan}}" class="form-control" placeholder="Masukkan Harga Beli Satuan" aria-label="Username" aria-describedby="basic-addon1">
                              </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword4">Jumlah Beli</label>
                            <input type="number" min="0" class="form-control"  value="{{$belanjas->jumlah_beli}}" name="jumlah_beli" placeholder="Masukkan Jumlah Beli">
                        </div>
                        <div class="form-group">
                            <label for="inputAddress">Tanggal Belanja</label>
                            <input type="date" name="tanggal" value="{{$belanjas->tanggal_pengeluaran}}"  class="form-control" aria-label="Username" aria-describedby="basic-addon1">
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
    $('#masking1').mask('#.##0', {reverse: true});
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
