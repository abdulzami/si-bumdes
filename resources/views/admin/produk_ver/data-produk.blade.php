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
                <h1 class="m-0 text-dark">List <span class="badge badge-primary">Produk</span></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('login') }}">Home</a></li>
                    <li class="breadcrumb-item active">List Produk</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@endsection
@section('sb-produk')
    bg-light
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Data Produk
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 20px">No.</th>
                                        <th>Nama Produk</th>
                                        <th>Kode Produk</th>
                                        <th>Harga Beli (Satuan)</th>
                                        <th><span class="badge badge-dark">Stok</span></th>
                                        <th>Harga Jual Pelanggan (Satuan)</th>
                                        <th>Harga Jual Umum (Satuan)</th>
                                        <th>Tanggal Produk Masuk</th>
                                        @if (Auth::user()->level == "admin")
                                        <th>Aksi</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($produks as $index => $produk)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{$produk->nama }}</td>
                                            <td>{{$produk->kode_produk }}</td>
                                            <td>@currency2($produk->harga_beli_satuan)</td>
                                            <td>{{$produk->stok}}</td>
                                            <td>@currency2($produk->harga_pelanggan_satuan)</td>
                                            <td>@currency2($produk->harga_umum_satuan)</td>
                                            <td>{{date('d-m-Y',strtotime($produk->tanggal_barang_masuk))}}</td>
                                            @if (Auth::user()->level == "admin")
                                            <td>
                                                @php
                                                $i = Crypt::encryptString($produk->id);
                                                @endphp
                                                <a href="{{route('produk.edit-harga',$i)}}"
                                                    class="btn btn-sm btn-outline-primary mb-3"><i class="fa fa-edit ">
                                                    </i> Edit Harga
                                                </a>
                                            </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th style="width: 20px">No.</th>
                                        <th>Nama Produk</th>
                                        <th>Kode Produk</th>
                                        <th>Harga Beli (Satuan)</th>
                                        <th><span class="badge badge-dark">Stok</span></th>
                                        <th>Harga Jual Pelanggan (Satuan)</th>
                                        <th>Harga Jual Umum (Satuan)</th>
                                        <th>Tanggal Produk Masuk</th>
                                        @if (Auth::user()->level == "admin")
                                        <th>Aksi</th>
                                        @endif
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
    <script src={{ asset('assets/plugins/select2/js/select2.full.min.js') }}></script>
    <script src={{ asset('assets/plugins/toastr/toastr.min.js') }}></script>
    <script src={{ asset('assets/sweetalert2.all.js') }}></script>
   <script>
       $(".swall-yeah").click(function(e){
        let id = e.target.dataset.id;
        Swal.fire({
            title: 'Apakah anda yakin ingin menghapus data ini ?',
            text: "Anda tidak akan bisa mengembalikan nya!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus !'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#delete'+id).submit();
            }
        })
       })
   </script>
   <script>
        $(".swall-yeah-valid").click(function(e){
        let id = e.target.dataset.id;
        Swal.fire({
            title: 'Apakah anda yakin ingin mem-validasi data ini ?',
            text: "Anda tidak akan bisa meng-edit maupun men-delete nya lagi!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Validasi !'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#validasi'+id).submit();
            }
        })
        })
    </script>
    <script>
        $(function() {
            $("#example1").DataTable({
                "autoWidth": false,
            });
        });

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
