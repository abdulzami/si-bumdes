@extends('layouts.master')
@push('custom-css')
<link rel="stylesheet" href={{ asset('assets/plugins/toastr/toastr.min.css') }}>
@endpush
@section('content-header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Edit Jasa<span class="badge badge-info"></span></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('login') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('jasa') }}">Manajemen Jasa</a></li>
                    <li class="breadcrumb-item active">Edit Jasa</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@endsection
@section('sb-jasa')
    bg-light
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Tambah Jasa<div class="badge badge-info"></div>
                    </h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{route('jasa.update',$id)}}">
                        @csrf
                        @method('put')
                        <div class="form-group">
                            <label for="inputAddress">Nama Jasa</label>
                            <input type="text" class="form-control" value="{{$jasa->name}}" name="nama_jasa" placeholder="Masukkan Nama Jasa">
                        </div>
                        <div class="form-group">
                            <label for="inputAddress">Kode Jasa</label>
                            <input type="text" class="form-control" value="{{$jasa->kode_jasa}}" name="kode_jasa" placeholder="Masukkan Kode Jasa">
                        </div>
                        <div class="form-row">
                          <div class="form-group col-md-6">
                            <label for="inputEmail4">Harga Pelanggan</label>
                            <input type="number" min="0" class="form-control" value="{{$jasa->harga_pelanggan}}" name="harga_pelanggan" placeholder="Masukkan Harga Pelanggan">
                          </div>
                          <div class="form-group col-md-6">
                            <label for="inputPassword4">Harga Umum</label>
                            <input type="number" min="0" class="form-control" name="harga_umum" value="{{$jasa->harga_umum}}" placeholder="Masukkan Harga Umum">
                          </div>
                        </div>
                        <div class="form-group">
                            <label for="inputAddress">Tanggal Jasa</label>
                            <input type="date" name="tanggal" class="form-control" value="{{$jasa->tanggal_jasa}}" aria-label="Username" aria-describedby="basic-addon1">
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
