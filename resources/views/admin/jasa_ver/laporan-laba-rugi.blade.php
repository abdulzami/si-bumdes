@extends('layouts.master')
@push('custom-css')
<link rel="stylesheet" href={{ asset('assets/plugins/toastr/toastr.min.css') }}>
@endpush
@section('content-header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Laporan Laba Rugi</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('login') }}">Home</a></li>
                    <li class="breadcrumb-item active">Laporan Laba Rugi Jasa</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@endsection
@section('sb-laporan-labarugi')
    bg-light
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <a href="{{ route('laporan-labarugi-jasa.print') }}" class="btn btn-outline-primary mb-3"><i class="fa fa-print ">
            </i> Cetak Semua Laba Rugi</a>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Filter Laporan Laba Rugi<div class="badge badge-info"></div>
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('laporan-labarugi-jasa.filter-print') }}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                              <label for="inputEmail4">Tanggal Awal</label>
                              <input type="date" name="tanggal_awal" class="form-control" id="inputEmail4">
                            </div>
                            <div class="form-group col-md-6">
                              <label for="inputPassword4">Tanggal Akhir</label>
                              <input type="date" name="tanggal_akhir" class="form-control" id="inputPassword4">
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Cetak Filter</button>
                        </div>
                    </form>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
        <!-- /.col-md-6 -->
        <div class="col-lg-6">

        </div>
        <!-- /.col-md-6 -->
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
