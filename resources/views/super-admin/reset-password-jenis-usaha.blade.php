@extends('layouts.master')
@push('custom-css')
<link rel="stylesheet" href={{ asset('assets/plugins/toastr/toastr.min.css') }}>
@endpush
@section('content-header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Reset Password Jenis Usaha</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('login') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('jenis-usaha') }}">Manajemen Jenis Usaha</a></li>
                    <li class="breadcrumb-item active">Reset Password Jenis Usaha</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@endsection
@section('sb-jenis-usaha')
    bg-light
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Reset Password Jenis Usaha <div class="badge badge-info"></div>
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('jenis-usaha.update-password',$id) }}" method="POST">
                        @method('put')
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Password Baru</label>
                                    <input type="password" name="password" class="form-control"
                                        placeholder="Masukan Password Baru">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Ulangi Password</label>
                                    <input type="password" name="ulangi_password" class="form-control"
                                        placeholder="Masukan Password">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success">Simpan</button>
                                </div>
                            </div>
                        </div>
                            <!-- /.row -->
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
