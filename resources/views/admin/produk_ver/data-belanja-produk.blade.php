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
                <h1 class="m-0 text-dark">Pengeluaran <span class="badge badge-primary">Belanja Produk</span></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('login') }}">Home</a></li>
                    <li class="breadcrumb-item active">Pengeluaran Belanja Produk</li>
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
    <div class="container-fluid">
        <a href="{{ route('belanja-produk.create') }}" class="btn btn-outline-dark mb-3"><i class="fa fa-plus ">
        </i> Tambah</a>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Data Belanja Produk
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
                                        <th>Jumlah Beli</th>
                                        <th>Total Biaya</th>
                                        <th>Tanggal Pengeluaran</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($belanjas as $index => $belanja)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{$belanja->nama }}</td>
                                            <td>{{$belanja->kode_produk }}</td>
                                            <td>@currency2($belanja->harga_beli_satuan)</td>
                                            <td>{{$belanja->jumlah_beli }}</td>
                                            <td>@currency2($belanja->total_biaya)</td>
                                            <td>{{date('d-m-Y',strtotime($belanja->tanggal_pengeluaran))}}</td>
                                            <td>
                                                @php
                                                $i = Crypt::encryptString($belanja->id_pengeluaran);
                                                @endphp
                                                @if ($belanja->id_belanja == null)
                                                <a href="{{route('belanja_prduk.to-produk',$i)}}" class="btn btn-sm btn-outline-dark  mb-3"><i class="fa fa-check "></i> Masukkan ke list produk
                                                </a>
                                                <a href="{{route('belanja-produk.edit',$i)}}"
                                                    class="btn btn-sm btn-outline-primary mb-3"><i class="fa fa-edit ">
                                                    </i> Edit
                                                </a>
                                                <a href="#" data-id="{{$i}}" class="btn btn-sm btn-outline-danger mb-3 swall-yeah">
                                                    <form action="{{route('belanja-produk.delete',$i)}}" method="POST" id="delete{{$i}}">
                                                        @csrf
                                                        @method('delete')
                                                    </form><i class="fa fa-trash-alt "></i> Hapus
                                                </a>
                                                @else
                                                <span class="badge badge-dark">Belanja ini sudah masuk list produk</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th style="width: 20px">No.</th>
                                        <th>Nama Produk</th>
                                        <th>Kode Produk</th>
                                        <th>Harga Beli (Satuan)</th>
                                        <th>Jumlah Beli</th>
                                        <th>Total Biaya</th>
                                        <th>Tanggal Pengeluaran</th>
                                        <th>Aksi</th>
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
        $(function() {
            $("#example1").DataTable({
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
