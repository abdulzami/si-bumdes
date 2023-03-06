@extends('layouts.master')
@push('custom-css')
    <link rel="stylesheet" href={{ asset('assets/plugins/toastr/toastr.min.css') }}>
@endpush
@section('sb-profil')
    bg-light
@endsection
@section('content-header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Profil anda</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('login') }}">Home</a></li>
                    <li class="breadcrumb-item active">Profil anda</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
      <div class="col-md-3">

        <!-- Profile Image -->
        <div class="card card-dark card-outline">
          <div class="card-body box-profile">
            {{-- <div class="text-center">
              <img class="profile-user-img img-fluid img-circle"
                   src="../../dist/img/user4-128x128.jpg"
                   alt="User profile picture">
            </div> --}}

            <h3 class="profile-username text-center">{{Auth::user()->name}}</h3>

            <p class="text-muted text-center">{{Auth::user()->level}}</p>

            <ul class="list-group list-group-unbordered mb-3">
              <li class="list-group-item">
                <b>Username</b> <a class="float-right">{{Auth::user()->username}}</a>
              </li>
              @if (Auth::user()->level == 'kasir')
              <li class="list-group-item">
                <b>Wujud usaha</b> <a class="float-right">{{$nama_usaha->wujud_usaha}}</a>
              </li>
                @endif
                @if (Auth::user()->level == 'admin')
              <li class="list-group-item">
                <b>Wujud usaha</b> <a class="float-right">{{Auth::user()->wujud_usaha}}</a>
              </li>
                @endif
                @if (Auth::user()->level == "kasir")
              <li class="list-group-item">
                <b>Kasir Di Usaha</b> <a class="float-right">
                 {{$nama_usaha->name}}
                </a>
              </li>
            @endif
            </ul>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
      <div class="col-md-9">
        <div class="card">
          <div class="card-header p-2">
            <ul class="nav nav-pills">
              <li class="nav-item"><a class="nav-link active" href="#timeline" data-toggle="tab">Ubah Identitas</a></li>
              <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Ubah Password</a></li>
            </ul>
          </div><!-- /.card-header -->
          <div class="card-body">
            <div class="tab-content">
              <!-- /.tab-pane -->
              <div class="active tab-pane" id="timeline">
                <!-- The timeline -->
                <form class="form-horizontal" action="{{route('profil.update-identitas',Crypt::encryptString(Auth::user()->id))}}" method="post">
                  @method('put')
                  @csrf
                    <div class="form-group row">
                      <label for="inputName" class="col-sm-2 col-form-label">
                        @if (Auth::user()->level == "admin")
                        Nama Usaha  
                        @elseif(Auth::user()->level == "kasir")
                        Nama Kasir
                        @elseif(Auth::user()->level == "super-admin")
                        Nama
                        @endif 
                      </label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" value="{{Auth::user()->name}}" name="nama" placeholder="Masukkan Nama">
                      </div>
                    </div>
                    @if (Auth::user()->level == "admin" || Auth::user()->level == "super-admin")
                    <div class="form-group row">
                      <label for="inputName" class="col-sm-2 col-form-label">
                        @if (Auth::user()->level == "super-admin")
                        Nama Ketua Bumdes
                        @endif
                        @if (Auth::user()->level == "admin")
                        Nama Kepala Usaha
                        @endif
                      </label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" value="{{Auth::user()->nama_kepala_usaha}}" name="kepala_usaha" placeholder="Masukkan Nama">
                      </div>
                    </div>
                    @endif
                    <div class="form-group row">
                      <div class="offset-sm-2 col-sm-10">
                        <button type="submit" class="btn btn-success">Perbarui</button>
                      </div>
                    </div>
                  </form>
              </div>
              <!-- /.tab-pane -->

              <div class="tab-pane" id="settings">
                <form class="form-horizontal" action="{{route('profil.update-password',Crypt::encryptString(Auth::user()->id))}}" method="post">
                  @method('put')
                  @csrf
                  <div class="form-group row">
                    <label for="inputEmail" class="col-sm-2 col-form-label">Password baru</label>
                    <div class="col-sm-10">
                      <input type="password" class="form-control" name="password" id="inputEmail" placeholder="Masukkan Password Baru">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputName2" class="col-sm-2 col-form-label">Ulangi password baru</label>
                    <div class="col-sm-10">
                      <input type="password" class="form-control" name="ulangi_password" id="inputName2" placeholder="Ulangi Password Baru">
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="offset-sm-2 col-sm-10">
                      <button type="submit" class="btn btn-success">Perbarui</button>
                    </div>
                  </div>
                </form>
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div><!-- /.card-body -->
        </div>
        <!-- /.nav-tabs-custom -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div><!-- /.container-fluid -->
@endsection
@push('custom-script')
<script src={{ asset('assets/plugins/toastr/toastr.min.js') }}></script>
@if (session('success'))
<script>
    toastr.success('{{ session('success') }}');

</script>
@endif
@if (session('gagal'))
<script>
    toastr.warning('{{ session('gagal') }}');

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
