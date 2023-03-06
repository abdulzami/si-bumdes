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
                <h1 class="m-0 text-dark">Manajemen <span class="badge badge-primary">Jenis Usaha</span></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('login') }}">Home</a></li>
                    <li class="breadcrumb-item active">Manajemen Jenis Usaha</li>
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
        <a href="{{ route('jenis-usaha.create') }}" class="btn btn-outline-dark mb-3"><i class="fa fa-plus ">
            </i>Tambah</a>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Data Jenis Usaha <div class="badge badge-info"></div>
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 20px">No.</th>
                                    <th>Nama Usaha</th>
                                    <th>Admin Username</th>
                                    <th>Nama Kepala Usaha</th>
                                    <th>Wujud Usaha</th>
                                    <th style="width: 300px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jenis_usahas as $index => $jenis_usaha)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $jenis_usaha->name }}
                                        </td>
                                        <td>{{ $jenis_usaha->username }}</td>
                                        <td>{{ $jenis_usaha->nama_kepala_usaha }}</td>
                                        <td>
                                            @if ($jenis_usaha->wujud_usaha == 'Jasa')
                                                <div class="badge badge-success">{{ $jenis_usaha->wujud_usaha }}</div>
                                            @else
                                                <div class="badge badge-primary">{{ $jenis_usaha->wujud_usaha }}</div>
                                            @endif

                                        </td>
                                        <td>
                                            @php
                                            $i = Crypt::encryptString($jenis_usaha->id);
                                            @endphp
                                            <a href="{{route('jenis-usaha.saldo-omset-profit',$i)}}" 
                                            class="btn btn-sm btn-outline-dark mb-3"><i class="fa fa-clipboard-list ">
                                            </i> Saldo-Omset-Profit</a>
                                            <a href="{{route('jenis-usaha.data-kas',$i)}}" 
                                            class="btn btn-sm btn-outline-dark mb-3"><i class="fa fa-clipboard-list ">
                                            </i> Data Kas</a>
                                            <a href="{{route('jenis-usaha.data-transaksi',$i)}}" 
                                            class="btn btn-sm btn-outline-dark mb-3"><i class="fa fa-clipboard-list ">
                                            </i> Data Transaksi</a>
                                            <a href="{{route('jenis-usaha.edit',$i)}}"
                                                class="btn btn-sm btn-outline-primary mb-3"><i class="fa fa-edit ">
                                                </i> Edit</a>
                                            <a href="#" data-id="{{$i}}" class="btn btn-sm btn-outline-danger mb-3 swall-yeah">
                                                <form action="{{route('jenis-usaha.delete',$i)}}" method="POST" id="delete{{$i}}">
                                                    @csrf
                                                    @method('delete')
                                                </form>
                                            <i class="fa fa-trash-alt ">
                                                </i> Hapus</a>

                                            <a href="{{route('jenis-usaha.reset-password',$i)}}" 
                                                class="btn btn-sm btn-outline-warning mb-3"><i class="fa fa-edit ">
                                                </i> Edit Password</a>
                                                
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th style="width: 20px">No.</th>
                                    <th>Nama Usaha</th>
                                    <th>Admin Username</th>
                                    <th>Nama Kepala Usaha</th>
                                    <th>Wujud Usaha</th>
                                    <th style="width: 300px">Aksi</th>
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
