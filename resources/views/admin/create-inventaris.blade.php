@extends('layouts.master')
@push('custom-css')
<link rel="stylesheet" href={{ asset('assets/plugins/toastr/toastr.min.css') }}>
@endpush
@section('content-header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Tambah Inventaris</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('login') }}">Home</a></li>
                    <li class="breadcrumb-item active"><a href="{{ route('inventaris') }}">Manajemen Inventaris</a></li>
                    <li class="breadcrumb-item active">Tambah Inventaris</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@endsection
@section('sb-inventaris')
    bg-light
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Tambah Inventaris<div class="badge badge-info"></div>
                    </h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{route('inventaris.store')}}">
                        @csrf
                        <div class="form-group">
                            <label for="inputAddress">Nama Barang</label>
                            <input type="text" class="form-control" name="nama_barang" placeholder="Masukkan Nama Barang">
                        </div>
                        <div class="form-group">
                            <label for="inputAddress">Kode Barang</label>
                            <input type="text" class="form-control" name="kode_barang" placeholder="Masukkan Kode Barang">
                        </div>
                        <div class="form-group">
                            <label for="inputAddress">Harga Barang</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">Rp</span>
                                <input type="text" name="harga_barang" id="masking1" class="form-control" placeholder="Masukkan Harga Barang" aria-label="Username" aria-describedby="basic-addon1">
                              </div>
                        </div>
                        <div class="form-group">
                            <label for="inputAddress">Umur Ekonomis (tahun)</label>
                            <input type="number" name="umur_ekonomis" class="form-control" placeholder="Masukkan Umur Ekonomis" aria-label="Username" aria-describedby="basic-addon1">
                        </div>
                        <div class="form-group">
                            <label for="inputAddress">Penyusutan Harga per Tahun (Beban Penyusutan)</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">Rp</span>
                                <input type="text" name="beban_penyusutan" id="masking2" class="form-control" placeholder="Masukkan Penyusustan Harga per Tahun" aria-label="Username" aria-describedby="basic-addon1">
                              </div>
                        </div>
                        <div class="form-group">
                            <label for="inputAddress">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" placeholder="Masukkan Penyusustan Harga per Tahun" aria-label="Username" aria-describedby="basic-addon1">
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
