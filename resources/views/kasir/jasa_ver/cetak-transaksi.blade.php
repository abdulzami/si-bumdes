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
<table width="800" border="0">
    <tr>
        <td align="left" width="100" rowspan="2"><img src="{{ asset('assets/dist/img/logo-bumdes.png') }}" alt="" width="100"></td>
    </tr>
    <tr>
        <td align="right"><b style="font-size:20px;">TRANSAKSI JASA BUMDES MUKTI BINA ARTA</b><br> Jl. Ky Sahlan No.34 Manyarsidomukti <br> Telp. 0896-7083-2333</td>
    </tr>
    <tr>
        <td align="right" colspan="2"><hr></td>
    </tr>
</table>
    <table width="800" border="0">
        <tr>
            <td>Nama Jenis Usaha : {{$nama_jenis_usaha->name}}</td>
            <td></td>
            <td>
                {{-- {{date('d F Y', strtotime($start))}} --}}
            </td>
        </tr>
        <tr>
            <td>Nama Transaksi : {{$transaksi->nama}}</td>
            <td></td>
            <td>
                {{-- {{date('d F Y', strtotime($start))}} --}}
            </td>
        </tr>
        <tr>
            <td>Nama Pelanggan :
            @if ($details[0]->nama_pelanggan)
            {{$details[0]->nama_pelanggan}}
            @else
            -
            @endif
            </td>
            <td></td>
            <td>
                {{-- {{date('d F Y', strtotime($end))}} --}}
            </td>
        </tr>
        <tr>
            <td>Tanggal Transaksi : {{date('d-m-Y',strtotime($transaksi->tanggal_pemasukan))}} </td>
            <td></td>
            <td>
                {{-- {{date('d F Y', strtotime($start))}} --}}
            </td>
        </tr>
        <tr>
            <td>Uang Bayar : @currency2($details[0]->bayar) </td>
            <td></td>
            <td>
                {{-- {{date('d F Y', strtotime($start))}} --}}
            </td>
        </tr>
        <tr>
            <td>Uang Kembalian : @currency2($details[0]->kembali) </td>
            <td></td>
            <td>
                {{-- {{date('d F Y', strtotime($start))}} --}}
            </td>
        </tr>
    </table><br>
    <table border="1" width="800">
        <thead>
            <th style="width: 20px">No.</th>
            <th>Nama Jasa</th>
            <th>Harga Satuan</th>
            <th>Jumlah</th>
            <th>Total</th>
        </thead>
        <tbody>
            @php
                $total_pemasukan = "";
            @endphp
            @foreach ($details as $index => $detail)
                <tr>
                    <td>{{$index+1}}.</td>
                    <td>{{$detail->nama_jasa}}</td>
                    @if ($detail->nama_pelanggan == null)
                    <td>@currency2($detail->harga_umum)</td>
                    @else
                    <td>@currency2($detail->harga_pelanggan)</td>
                    @endif
                    <td>{{$detail->jumlah}}</td>
                    <td>@currency2($detail->total)</td>
                    @php
                        $total_pemasukan = $detail->total_pemasukan
                    @endphp
                </tr>  
            @endforeach
            <tr>
                <td colspan="4">
                </td>
                <td>
                   <b> @currency2($total_pemasukan)</b>
                </td>
            </tr>
        </tbody>
        {{-- <tbody>
            @php
                $total_pemasukan = "";
            @endphp
            @foreach ($details as $index => $detail)
                <tr>
                    <td>{{$index+1}}.</td>
                    <td>{{$detail->name}}</td>
                    @if ($detail->nama_pelanggan == null)
                    <td><span class="badge badge-dark">Ini transaksi umum</span></td>
                    <td>@currency2($detail->harga_umum)</td>
                    @else
                    <td>{{$detail->nama_pelanggan}}</td>
                    <td>@currency2($detail->harga_pelanggan)</td>
                    @endif
                    <td>{{$detail->jumlah}}</td>
                    <td>@currency2($detail->total)</td>
                    @php
                        $total_pemasukan = $detail->total_pemasukan
                    @endphp
                </tr>  
            @endforeach
            <tr>
                <td colspan="5">
                    
                </td>
                <td>
                   <b> @currency2($total_pemasukan)</b>
                </td>
            </tr>
        </tbody> --}}
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
            <td>Kasir</td>
        </tr>
        <tr>
            <td><br><br><br></td>
            <td></td>
            <td><br><br><br>
                <u>
                {{$transaksi->nama_kasir}}</u>
            </td>
        </tr>
    </table>
    </center>
    <script type="text/javascript">
 window.print();
</script>
</body>
</html>