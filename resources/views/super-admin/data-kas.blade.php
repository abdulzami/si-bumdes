@extends('layouts.master')
@push('custom-css')
    <link rel="stylesheet" href={{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}>
    <link rel="stylesheet" href={{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}>
    {{-- <link rel="stylesheet" href={{ asset('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}> --}}
    <link rel="stylesheet" href={{ asset('assets/plugins/toastr/toastr.min.css') }}>
@endpush
@section('content-header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Data Kas {{$nama_usaha}}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('login') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('jenis-usaha') }}">Manajemen Jenis Usaha</a></li>
                    <li class="breadcrumb-item active">Data Kas</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@endsection
@section('sb-jenis-usaha')
    bg-light
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Filter Data Kas<div class="badge badge-info"></div>
                        </h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('jenis-usaha.filter-data-kas',$id) }}" method="POST">
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
                                <button type="submit" class="btn btn-primary">Filter</button>
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
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Data Kas
                            @if (session('kas'))
                                <span class="badge badge-primary">Terfilter</span>
                            @endif
                            <div class="badge badge-info"></div>
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 20px">No.</th>
                                    <th>Tipe</th>
                                    <th>Nama</th>
                                    <th>Tanggal</th>
                                    <th>Total</th>
                                    <th>Jenis</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $data_kas = $kases;
                                    if (session('kas')) {
                                        $data_kas = session('kas');
                                    }
                                    $i = 0;
                                @endphp
                                @foreach ($data_kas as $index => $kas)
                                    <tr>
                                        <td>{{ $i += 1 }}</td>
                                        <td>{{ str_replace('_',' ',$kas->tipe) }}</td>
                                        <td>{{ $kas->nama }}</td>
                                        <td>{{date('d-m-Y',strtotime($kas->tanggal))}}</td>
                                        <td>@currency2($kas->total)</td>
                                        <td>
                                            @if ($kas->jenis_kas == "pemasukan")
                                                <span class="badge badge-success">Pemasukan</span>
                                            @else
                                            <span class="badge badge-danger">Pengeluaran</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th style="width: 20px">No.</th>
                                    <th>Tipe</th>
                                    <th>Nama</th>
                                    <th>Tanggal</th>
                                    <th>Total</th>
                                    <th>Jenis</th>
                                </tr>
                            </tfoot>
                        </table>
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
            confirmButtonText: 'Yes, delete it!'
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
