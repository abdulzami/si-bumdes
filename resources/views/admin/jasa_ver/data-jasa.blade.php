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
                <h1 class="m-0 text-dark">Manajemen <span class="badge badge-success">Jasa</span></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('login') }}">Home</a></li>
                    <li class="breadcrumb-item active">Manajemen Jasa</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@endsection
@section('sb-jasa')
    bg-light
@endsection
@section('content')
    <div class="container-fluid">
        <a href="{{ route('jasa.create') }}" class="btn btn-outline-dark mb-3"><i class="fa fa-plus ">
            </i>Tambah</a>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Data Jasa
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 20px">No.</th>
                                        <th>Kode Jasa</th>
                                        <th>Nama Jasa</th>
                                        <th>Harga Pelanggan</th>
                                        <th>Harga Umum</th>
                                        <th>Tanggal Jasa</th>
                                        <th style="width: 300px">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($jasas as $index => $jasa)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $jasa->kode_jasa }}</td>
                                            <td>{{$jasa->name }}</td>
                                            <td>@currency2($jasa->harga_pelanggan)</td>
                                            <td>@currency2($jasa->harga_umum)</td>
                                            <td>{{date('d-m-Y',strtotime($jasa->tanggal_jasa))}}</td>
                                            <td>
                                                @php
                                                $i = Crypt::encryptString($jasa->id);
                                                @endphp
                                                 @if ($jasa->valid == 'no')
                                                 <a href="#" data-id="{{$i}}" class="btn btn-sm btn-outline-dark  mb-3 swall-yeah-valid">
                                                     <form action="{{route('jasa.validasi',$i)}}" method="POST" id="validasi{{$i}}">
                                                         @csrf
                                                         @method('put')
                                                     </form><i class="fa fa-check "></i> Validasi
                                                 </a>
                                                 <a href="{{route('jasa.edit',$i)}}"
                                                     class="btn btn-sm btn-outline-primary mb-3"><i class="fa fa-edit ">
                                                     </i> Edit
                                                 </a>
                                                 <a href="#" data-id="{{$i}}" class="btn btn-sm btn-outline-danger mb-3 swall-yeah">
                                                     <form action="{{route('jasa.delete',$i)}}" method="POST" id="delete{{$i}}">
                                                         @csrf
                                                         @method('delete')
                                                     </form><i class="fa fa-trash-alt "></i> Hapus
                                                 </a>
                                                 @else
                                                 <span class="badge badge-dark">Data ini sudah di validasi</span>
                                                 <a href="{{route('jasa.edit-harga',$i)}}"
                                                     class="btn btn-sm btn-outline-primary mt-2 mb-3"><i class="fa fa-edit ">
                                                     </i> Edit Harga
                                                 </a>
                                                 @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th style="width: 20px">No.</th>
                                        <th>Kode Jasa</th>
                                        <th>Nama Jasa</th>
                                        <th>Harga Pelanggan</th>
                                        <th>Harga Umum</th>
                                        <th>Tanggal Jasa</th>
                                        <th style="width: 300px">Aksi</th>
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
            confirmButtonText: 'Yes, hapus!'
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
        
        $('.select2').select2()

        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
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
