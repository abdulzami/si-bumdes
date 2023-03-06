@extends('layouts.master')
@push('custom-css')
    <link rel="stylesheet" href={{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}>
    <link rel="stylesheet" href={{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}>
    <link rel="stylesheet" href={{ asset('assets/plugins/select2/css/select2.min.css') }}>
    <link rel="stylesheet" href={{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}>
    <link rel="stylesheet" href={{ asset('assets/plugins/toastr/toastr.min.css') }}>
@endpush
@section('content-header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Data Inventaris Usaha di BUMDes</span></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('login') }}">Home</a></li>
                    <li class="breadcrumb-item active">Data Inventaris Usaha Usaha BUMDes</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@endsection
@section('sb-inventaris')
    bg-light
@endsection
@section('content')
    <div class="container-fluid">
        {{-- <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Cetak Inventaris<div class="badge badge-info"></div>
                        </h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('jenis-usaha.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                          <label for="inputEmail4">Email</label>
                                          <input type="email" class="form-control" id="inputEmail4">
                                        </div>
                                        <div class="form-group col-md-6">
                                          <label for="inputPassword4">Password</label>
                                          <input type="password" class="form-control" id="inputPassword4">
                                        </div>
                                      </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">Cetak</button>
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
        </div> --}}
        <a href="{{ route('inventaris-all.print') }}" class="btn btn-outline-primary mb-3"><i class="fa fa-print ">
        </i> Cetak</a>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Data Inventaris
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 20px">No.</th>
                                        <th>Nama Jenis Usaha</th>
                                        <th>Nama Barang</th>
                                        <th>Kode Barang</th>
                                        <th>Harga Awal</th>
                                        <th>Beban Penyusutan</th>
                                        <th>Umur Ekonomis</th>
                                        <th>Jumlah Penyusutan</th>
                                        <th>Harga Setelah Penyusutan</th>
                                        <th>Tanggal Awal (d-m-Y)</th>
                                        <th>Akan Menyusut Tanggal (d-m-Y)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($inventarises as $index => $inventaris)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{$inventaris->name }}</td>
                                            <td>{{$inventaris->nama_barang }}</td>
                                            <td>{{$inventaris->kode_barang }}</td>
                                            <td>@currency2($inventaris->harga)</td>
                                            <td>@currency2($inventaris->beban_penyusutan)</td>
                                            <td>{{$inventaris->umur_ekonomis }} tahun</td>
                                            <td><span class="badge badge-dark">{{$inventaris->jumlah_penyusutan }}</span> / {{$inventaris->umur_ekonomis }}</td>
                                            @php
                                            $harga_s_p = $inventaris->harga - ($inventaris->beban_penyusutan * $inventaris->jumlah_penyusutan);
                                            @endphp
                                            <td>@currency2($harga_s_p)</td>
                                            <td>{{date('d-m-Y',strtotime($inventaris->tanggal))}}</td>
                                            <td>
                                                
                                                @php
                                                    $plus = $inventaris->jumlah_penyusutan +1;
                                                    $futureDate=date('d-m-Y', strtotime('+'.$plus.' year', strtotime($inventaris->tanggal)) );
                                                @endphp
                                                @if ($inventaris->jumlah_penyusutan - $inventaris->umur_ekonomis == 0)
                                                <span class="badge badge-dark">Tidak akan menyusut lagi</span>
                                                @else
                                                {{$futureDate}}
                                                @endif
                                                
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th style="width: 20px">No.</th>
                                        <th>Nama Jenis Usaha</th>
                                        <th>Nama Barang</th>
                                        <th>Kode Barang</th>
                                        <th>Harga Awal</th>
                                        <th>Beban Penyusutan</th>
                                        <th>Umur Ekonomis</th>
                                        <th>Jumlah Penyusutan</th>
                                        <th>Harga Setelah Penyusutan</th>
                                        <th>Tanggal Awal (d-m-Y)</th>
                                        <th>Akan Menyusut Tanggal (d-m-Y)</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
            <!-- /.col-md-6 -->
            <div class="col-lg-6">

            </div>
            <!-- /.col-md-6 -->
        </div>
    </div><!-- /.container-fluid -->

@endsection
@push('custom-script')
    <script src={{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}></script>
    <script src={{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}></script>
    <script src={{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}></script>
    <script src={{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}></script>
    <script>
        $(function() {
            $("#example1").DataTable({
            });
        });

    </script>
    
@endpush
