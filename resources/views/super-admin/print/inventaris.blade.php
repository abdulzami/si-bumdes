<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventaris BUMDes</title>
    <style>
        table {
            border-collapse: collapse;
            }
        
            th, td {
            padding: 5px;
            }
    </style>
</head>
<body>
<center>
<table width="800">
    <tr>
        <td align="left" width="100" rowspan="2"><img src="{{ asset('assets/dist/img/logo-bumdes.png') }}" alt="" width="100"></td>
    </tr>
    <tr>
        <td align="right"><b style="font-size:20px;">DATA INVENTARIS BUMDES MUKTI BINA ARTA</b><br> Jl. Ky Sahlan No.34 Manyarsidomukti <br> Telp. 0896-7083-2333</td>
    </tr>
    <tr>
        <td align="right" colspan="2"><hr></td>
    </tr>
</table>
    {{-- <table width="800">
        <tr>
            <td>Mulai Tanggal</td>
            <td>:</td>
            <td>{{date('d F Y', strtotime($start))}}</td>
        </tr>
        <tr>
            <td>Sampai Tanggal</td>
            <td>:</td>
            <td>{{date('d F Y', strtotime($end))}}</td>
        </tr>
    </table> --}}
    <table border="1" width="800">
        <thead>
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
    </table><br>
    <table algin="right" width="800" border="0">
        <tr>
            <td></td>
            <td width="300"></td>
            <td>{{date('d F Y', strtotime(now()))}}</td>
        </tr>
        <tr>
            <td>
                {{-- Ketua Bumdes --}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td></td>
            <td>Mengetahui</td>
        </tr>
        <tr>
            <td>
                {{-- Ketua Bumdes --}}
            </td>
            <td></td>
            <td>Ketua Bumdes</td>
        </tr>
        <tr>
            <td><br><br><br></td>
            <td></td>
            <td><br><br><br><u>{{$ketua}}</u></td>
        </tr>
    </table>
    </center>
    <script type="text/javascript">
 window.print();
</script>
</body>
</html>