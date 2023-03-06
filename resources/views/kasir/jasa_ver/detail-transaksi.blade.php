@extends('layouts.master')
@push('custom-css')
<link rel="stylesheet" href={{ asset('assets/plugins/toastr/toastr.min.css') }}>
@endpush
@section('content-header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Detail Transaksi</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('login') }}">Home</a></li>
                    <li class="breadcrumb-item active"><a href="{{ route('transaksi-jasa') }}">Manajemen Transaksi</a></li>
                    <li class="breadcrumb-item active">Detail Transaksi </li>
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
                  <h3 class="card-title">Detail</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>                  
                              <tr>
                                <th style="width: 10px">No</th>
                                <th>Nama Jasa</th>
                                <th>Nama Pelanggan</th>
                                <th>Harga Satuan</th>
                                <th>Jumlah</th>
                                <th>Total</th>
                              </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total_pemasukan = "";
                                @endphp
                                @foreach ($details as $index => $detail)
                                    <tr>
                                        <td>{{$index+1}}.</td>
                                        <td>{{$detail->name}}</td>
                                        @if ($detail->nama_pelanggan == null)
                                        <td><span class="badge badge-dark">Ini transaksi umum</span></td>
                                        <td>@currency2($detail->harga_umum)</td>
                                        @else
                                        <td>{{$detail->nama_pelanggan}}</td>
                                        <td>@currency2($detail->harga_pelanggan)</td>
                                        @endif
                                        <td>{{$detail->jumlah}}</td>
                                        <td>@currency2($detail->total)</td>
                                        @php
                                            $total_pemasukan = $detail->total_pemasukan
                                        @endphp
                                    </tr>  
                                @endforeach
                                <tr>
                                    <td colspan="5">
                                        
                                    </td>
                                    <td>
                                       <b> @currency2($total_pemasukan)</b>
                                    </td>
                                </tr>
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
