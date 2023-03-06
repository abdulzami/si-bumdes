<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi BUMDes</title>
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
        <td align="right"><b style="font-size:20px;">LAPORAN TRANSAKSI PRODUK BUMDES MUKTI BINA ARTA</b><br> Jl. Ky Sahlan No.34 Manyarsidomukti <br> Telp. 0896-7083-2333</td>
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
            <tr>
                <td colspan="7" align="right">
                    <b>Total</b>
                </td>
                <td>
                    <b>@currency2($total)</b>
                </td>
                <td>
                    <b>@currency2($laba)</b>
                </td>
            </tr>
        </tbody>
    </table><br>
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