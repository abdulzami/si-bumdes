<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bon Hutang Pelanggan BUMDes</title>
    <style>
        table {
            border-collapse: collapse;
        }

        th,
        td {
            padding: 5px;
        }

    </style>
</head>

<body>
    <center>
        <table width="800" border="0">
            <tr>
                <td align="left" width="100" rowspan="2"><img src="{{ asset('assets/dist/img/logo-bumdes.png') }}"
                        alt="" width="100"></td>
            </tr>
            <tr>
                <td align="right"><b style="font-size:20px;">BON HUTANG PELANGGAN BUMDES MUKTI BINA ARTA</b><br> Jl. Ky
                    Sahlan No.34 Manyarsidomukti <br> Telp. 0896-7083-2333</td>
            </tr>
            <tr>
                <td align="right" colspan="2">
                    <hr>
                </td>
            </tr>
        </table>
        <table width="800">
            <tr>
                <td>Nama Jenis Usaha : {{ $bon_hutangs[0]->nama_jenis_usaha }}</td>
                <td></td>
                <td>
                    
                </td>
            </tr>
            {{-- <tr>
                <td>Nama Transaksi : {{ $transaksi->nama }}</td>
                <td></td>
                <td>
                    
                </td>
            </tr> --}}
            <tr>
                <td>Nama Pelanggan :
                    {{$bon_hutangs[0]->nama_pelanggan}}
                </td>
                <td></td>
                <td>
                    
                </td>
            </tr>
            {{-- <tr>
                <td>Tanggal Transaksi : {{ date('d-m-Y', strtotime($transaksi->tanggal_pemasukan)) }} </td>
                <td></td>
                <td>
                    
                </td>
            </tr> --}}
        </table><br>
        <table border="1" width="800">
            <thead>
                <th style="width: 20px">No.</th>
                <th>Nama Hutang</th>
                <th>Total Hutang</th>
                <th>Tanggal Hutang</th>
                <th>Tanggal Lunas</th>
                <th>Status Hutang</th>
            </thead>
            <tbody>
                @php
                    $total_hutang = 0;
                @endphp
                @foreach ($bon_hutangs as $index => $hutang)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $hutang->nama }}</td>
                        <td>@currency2($hutang->total_biaya)</td>
                        <td>{{ date('d-m-Y', strtotime($hutang->tanggal_pengeluaran)) }}</td>
                        <td>
                            @if ($hutang->tanggal_pemasukan)
                                {{ date('d-m-Y', strtotime($hutang->tanggal_pemasukan)) }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if ($hutang->status_hutang == 'belum lunas')
                                @php
                                    $total_hutang += $hutang->total_biaya;
                                @endphp
                                <span class="badge badge-danger">Belum Lunas</span>
                            @else
                                <span class="badge badge-success">Lunas</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="5" align="right">
                        <b>Hutang</b>
                    </td>
                    <td>
                        <b> @currency2($total_hutang)</b>
                    </td>
                </tr>
            </tbody>
        </table><br>
        <table algin="right" width="800" border="0">
            <tr>
                <td></td>
                <td width="300"></td>
                <td>{{ date('d F Y', strtotime(now())) }}</td>
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
                        {{ Auth::user()->name}}
                    </u>
                </td>
            </tr>
        </table>
    </center>
    <script type="text/javascript">
        window.print();

    </script>
</body>

</html>
