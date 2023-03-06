@extends('layouts.master')
@push('custom-css')
<link rel="stylesheet" href={{ asset('assets/plugins/toastr/toastr.min.css') }}>
<link rel="stylesheet" href={{ asset('assets/plugins/select2/css/select2.min.css') }}>
<link rel="stylesheet" href={{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}>
@endpush
@section('content-header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Edit Pengeluaran Beban Gaji </h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('login') }}">Home</a></li>
                    <li class="breadcrumb-item active"><a href="{{ route('beban-gaji') }}">Pengeluaran Beban Gaji</a></li>
                    <li class="breadcrumb-item active">Edit Pengeluaran Beban Gaji</li>
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
@section('sb-beban-gaji')
    bg-light
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Edit Beban Gaji<div class="badge badge-info"></div>
                    </h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{route('beban-gaji.update',$id)}}">
                        @csrf
                        @method('put')
                        <div class="form-group">
                            <label for="inputAddress">Nama Beban Gaji</label>
                            <input type="text" class="form-control" value="{{$beban_gaji->nama}}" name="nama_beban_gaji" placeholder="Masukkan Nama Beban Operasional">
                        </div>
                        <div class="form-group">
                            <label>Kasir</label>
                            <select class="form-control" name="kasir">
                              <option value="">Masukkan Kasir</option>
                              @foreach ($kasir as $index => $kasirnya)
                              <option 
                              @if ($kasirnya->id == $beban_gaji->id_karyawan)
                                  selected
                              @endif
                              value="{{Crypt::encryptString($kasirnya->id)}}">{{$kasirnya->name}}</option>
                              @endforeach
                            </select>
                          </div>
                        <div class="form-group">
                            <label for="inputAddress">Total Gaji</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">Rp</span>
                                <input type="text" name="total_gaji" id="masking2" value="{{$beban_gaji->total_biaya}}" class="form-control" placeholder="Masukkan Total Gaji" aria-label="Username" aria-describedby="basic-addon1">
                              </div>
                        </div>
                        <div class="form-group">
                            <label for="inputAddress">Tanggal Pengeluaran</label>
                            <input type="date" name="tanggal" value="{{$beban_gaji->tanggal_pengeluaran}}" class="form-control" aria-label="Username" aria-describedby="basic-addon1">
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
<script src={{ asset('assets/plugins/select2/js/select2.full.min.js') }}></script>
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
