@extends('layouts.master')
@push('custom-css')
<link rel="stylesheet" href={{ asset('assets/plugins/toastr/toastr.min.css') }}>
@endpush
@section('content-header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Edit Pemasukan Bebas<span class="badge badge-info"></span></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('login') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('pemasukan-bebas') }}">Pemasukan Pemasukan Bebas</a></li>
                    <li class="breadcrumb-item active">Edit Pemasukan Bebas</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@endsection
@section('sb-open-pemasukan')
    menu-open
@endsection
@section('sb-block-pemasukan')
    style="display: block"
@endsection
@section('sb-pemasukan-bebas')
    bg-light
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Edit Pemasukan Bebas<div class="badge badge-info"></div>
                    </h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{route('pemasukan-bebas.update',$id)}}">
                        @csrf
                        @method('put')
                        <div class="form-group">
                            <label for="inputAddress">Nama Pemasukan</label>
                            <input type="text" class="form-control" value="{{$pemasukan_bebas->nama}}" name="nama_pemasukan" placeholder="Masukkan Nama Produk">
                        </div>
                        <div class="form-group">
                            <label for="inputAddress">Total Dana Masuk</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">Rp</span>
                                <input type="text" name="total_dana_masuk" id="masking2" min="0"  value="{{$pemasukan_bebas->total}}" class="form-control" placeholder="Masukkan Total Biaya" aria-label="Username" aria-describedby="basic-addon1">
                              </div>
                        </div>
                        <div class="form-group">
                            <label for="inputAddress">Tanggal Pemasukan</label>
                            <input type="date" name="tanggal" class="form-control" value="{{$pemasukan_bebas->tanggal_pemasukan}}" aria-label="Username" aria-describedby="basic-addon1">
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
