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
                <h1 class="m-0 text-dark">Manajamen <span class="badge badge-primary">Inventaris</span></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('login') }}">Home</a></li>
                    <li class="breadcrumb-item active">Manajemen Inventaris</li>
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
        <a href="{{ route('inventaris.create') }}" class="btn btn-outline-dark mb-3"><i class="fa fa-plus ">
        </i> Tambah</a>
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
                                        <th>Nama Barang</th>
                                        <th>Kode Barang</th>
                                        <th>Harga Awal</th>
                                        <th>Beban Penyusutan</th>
                                        <th>Umur Ekonomis</th>
                                        <th>Jumlah Penyusutan</th>
                                        <th>Harga Setelah Penyusutan</th>
                                        <th>Tanggal Awal (d-m-Y)</th>
                                        <th>Akan Menyusut Tanggal (d-m-Y)</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($inventarises as $index => $inventaris)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
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
                                            <td>
                                                @php
                                                $i = Crypt::encryptString($inventaris->id);
                                                @endphp
                                                @if($inventaris->jumlah_penyusutan == 0)
                                                <a href="{{route('inventaris.edit',$i)}}"
                                                    class="btn btn-sm btn-outline-primary mb-3"><i class="fa fa-edit ">
                                                    </i> Edit
                                                </a>
                                                @endif
                                                <a href="#" data-id="{{$i}}" class="btn btn-sm btn-outline-danger mb-3 swall-yeah">
                                                    <form action="{{route('inventaris.delete',$i)}}" method="POST" id="delete{{$i}}">
                                                        @csrf
                                                        @method('delete')
                                                    </form><i class="fa fa-trash-alt "></i> Hapus
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th style="width: 20px">No.</th>
                                        <th>Nama Barang</th>
                                        <th>Kode Barang</th>
                                        <th>Harga Awal</th>
                                        <th>Beban Penyusutan</th>
                                        <th>Umur Ekonomis</th>
                                        <th>Jumlah Penyusutan</th>
                                        <th>Harga Setelah Penyusutan</th>
                                        <th>Tanggal Awal (d-m-Y)</th>
                                        <th>Akan Menyusut Tanggal (d-m-Y)</th>
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
