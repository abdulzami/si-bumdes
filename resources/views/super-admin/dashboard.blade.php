@extends('layouts.dashboard')
@section('content')
    <div class="container-fluid">
        <div class="jumbotron jumbotron-fluid">
            <div class="container">
              <h1 class="display-4">Selamat Datang, {{Auth::user()->nama_kepala_usaha}}</h1>
              <p class="lead"></p>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-6 col-md-6">
              <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-store"></i></span>
  
                <div class="info-box-content">
                  <span class="info-box-text">Jumlah Jenis Usaha</span>
                  <span class="info-box-number">
                    {{$jumlah_jenis_usaha}}
                  </span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-12 col-sm-6 col-md-6">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-users"></i></span>
  
                <div class="info-box-content">
                  <span class="info-box-text">Jumlah Kasir</span>
                  <span class="info-box-number">
                    {{$jumlah_kasir}}
                  </span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            <!-- /.col -->
  
            <!-- fix for small devices only -->
            <div class="clearfix hidden-md-up"></div>
  
            <!-- /.col -->
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
@endsection
