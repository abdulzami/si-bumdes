@extends('layouts.master')
@push('custom-css')
    <link rel="stylesheet" href={{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}>
    <link rel="stylesheet" href={{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}>
    <link rel="stylesheet" href={{ asset('assets/plugins/select2/css/select2.min.css') }}>
    <link rel="stylesheet" href={{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}>
    {{-- <link rel="stylesheet" href={{ asset('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}> --}}
    <link rel="stylesheet" href={{ asset('assets/plugins/toastr/toastr.min.css') }}>
@endpush
@section('content-header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Manajemen <span class="badge badge-warning">Kasir</span></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('login') }}">Home</a></li>
                    <li class="breadcrumb-item active">Manajemen Kasir</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@endsection
@section('sb-kasir')
    bg-light
@endsection
@section('content')
    <div class="container-fluid">
        @foreach ($data as $index => $kasir)
        <a href="{{ route('kasir.create',Crypt::encryptString($kasir->id)) }}" class="btn btn-outline-dark mb-3"><i class="fa fa-plus ">
        </i>Tambah</a>
        <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><b>{{$index + 1}}.</b> Data Kasir {{$kasir->name}}</h3><br>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                  <table class="table table-hover text-nowrap">
                    <thead>
                      <tr>
                        <th style="width: 10px">No.</th>
                        <th>Nama Kasir</th>
                        <th>Username</th>
                        <th style="width: 300px">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                        @if ($kasir->kasir == "[]")
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada kasir</td>
                            </tr>
                        @endif
                        @foreach ($kasir->kasir as $index2 => $kasirnya)
                          <tr>
                            <td>{{ $index2+1}}.</td>
                            <td>
                              {{$kasirnya->name}}
                            </td>
                            <td>
                              {{$kasirnya->username}}
                            </td>
                            <td>
                                <a href="{{route('kasir.edit',Crypt::encryptString($kasirnya->id))}}"
                                    class="btn btn-sm btn-outline-primary mb-3"><i class="fa fa-edit ">
                                    </i> Edit</a>
                                    @php
                                        $i = Crypt::encryptString($kasirnya->id);
                                    @endphp
                                <a href="#" data-id="{{$i}}" class="btn btn-sm btn-outline-danger mb-3 swall-yeah">
                                    <form action="{{route('kasir.delete',$i)}}" method="POST" id="delete{{$i}}" method="POST">
                                        @csrf
                                        @method('delete')
                                    </form>
                                <i class="fa fa-trash-alt ">
                                    </i> Hapus</a>

                                <a href="{{route('kasir.reset-password',$i)}}" 
                                    class="btn btn-sm btn-outline-warning mb-3"><i class="fa fa-edit ">
                                    </i> Edit Password</a>
                            </td>
                          </tr>
                        @endforeach
                    </tbody>
                  </table>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
        </div>
        @endforeach
    </div><!-- /.container-fluid -->
@endsection
@push('custom-script')
    <script src={{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}></script>
    <script src={{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}></script>
    <script src={{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}></script>
    <script src={{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}></script>
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
                "responsive": true,
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
