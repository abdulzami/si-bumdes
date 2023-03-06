<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laba Rugi BUMDes</title>
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
        <td align="right"><b style="font-size:20px;">LAPORAN LABA RUGI PRODUK BUMDES MUKTI BINA ARTA</b><br> Jl. Ky Sahlan No.34 Manyarsidomukti <br> Telp. 0896-7083-2333</td>
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
    <h3>Transaksi</h3>
    <table border="1" width="800">
        <thead>
            <th style="width: 20px">No.</th>
            <th>Nama Pelanggan</th>
            <th>Tanggal Transaksi</th>
            <th>Nama Produk</th>
            <th>Jumlah</th>
            <th>Harga Beli Satuan</th>
            <th>Harga Jual Satuan</th>
            <th>Total</th>
            <th>Laba</th>
        </thead>
        <tbody>
            @php
            $laba = 0;    
            $total = 0;
            $i = 0;
            @endphp
            @foreach ($transaksis as $index => $transaksi)
            <tr>
                @php
                    $total += $transaksi->total;
                @endphp
                <td>{{$i += 1}}</td>
                <td align="center">
                @if ($transaksi->nama_pelanggan == null)
                    -
                @else
                {{$transaksi->nama_pelanggan}}
                @endif    
                </td>
                <td>{{date('d-m-Y',strtotime($transaksi->tanggal_pemasukan))}}</td>
                <td>{{$transaksi->nama_produk}}</td>
                <td>{{$transaksi->jumlah}}</td>
                <td>@currency2($transaksi->harga_beli_satuan)</td>
                <td>
                    @if ($transaksi->nama_pelanggan == null)
                        @currency2($transaksi->harga_umum_satuan)
                    @else
                        @currency2($transaksi->harga_pelanggan_satuan)
                    @endif
                </td>
                <td>@currency2($transaksi->total)</td>
                <td>
                    @php
                        $laba += $transaksi->total - ($transaksi->harga_beli_satuan * $transaksi->jumlah);
                    @endphp
                    @currency2($transaksi->total - ($transaksi->harga_beli_satuan * $transaksi->jumlah))
                </td>
            </tr>
            @endforeach
            
        </tbody>
    </table><br>
    <b>Total Laba @currency2($laba)</b><br><br>
    <table border="1" width="800">
        <thead>
            <th style="width: 20px">No.</th>
            <th>Tipe</th>
            <th>Nama</th>
            <th>Tanggal</th>
            <th>Biaya Pengeluaran</th>
        </thead>
        <tbody>
            @php
                $t_pengeluaran = 0;
                $i = 0;
            @endphp
            @foreach ($kases as $index => $kas)
            @if ($kas->jenis_kas == 'pengeluaran')
            @php
                $t_pengeluaran += $kas->total;
            @endphp 
            @endif
                <tr>
                    <td>{{ $i += 1  }}</td>
                    <td>{{str_replace('_',' ',$kas->tipe)}}</td>
                    <td>{{$kas->nama }}</td>
                    <td>{{date('d-m-Y',strtotime($kas->tanggal))}}</td>
                    <td>
                        @if ($kas->jenis_kas == 'pengeluaran')
                        @currency2($kas->total)
                        @else
                        -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table><br>
    <b>Total Biaya Pengeluaran @currency2($t_pengeluaran)</b><br><br>
    <b>Profit @currency2($laba-$t_pengeluaran)</b><br><br>
    <h3>
        @if ($laba-$t_pengeluaran < 0)
            (Rugi)
        @else
            (Tidak Rugi)
        @endif
    </h2><br><br>
    <table algin="right" width="800" border="0">
        <tr>
            <td>Mengetahui</td>
            <td>
                {{-- Ketua Bumdes --}}
            </td>
            <td>
                {{date('d F Y', strtotime(now()))}}
            </td>
        </tr>
        <tr>
            <td>Ketua Bumdes</td>
            <td>
                {{-- Ketua Bumdes --}}
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td>Kepala Usaha {{Auth::user()->name}}</td>
        </tr>
        <tr>
            <td><br><br><br><u>{{$ketua->ketua}}</u></td>
            <td><br><br><br></td>
            <td><br><br><br><u>{{Auth::user()->nama_kepala_usaha}}</u></td>
        </tr>
    </table>
    </center>
    <script type="text/javascript">
 window.print();
</script>
</body>
</html>