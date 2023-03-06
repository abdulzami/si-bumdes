<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kas BUMDes</title>
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
        <td align="right"><b style="font-size:20px;">LAPORAN KAS BUMDES MUKTI BINA ARTA</b><br> Jl. Ky Sahlan No.34 Manyarsidomukti <br> Telp. 0896-7083-2333</td>
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
            <th>Tipe</th>
            <th>Nama</th>
            <th>Tanggal</th>
            <th>Pemasukan</th>
            <th>Pengeluaran</th>
            <th>Saldo</th>
        </thead>
        <tbody>
            @php
                $saldo = 0;
                $t_pemasukan = 0;
                $t_pengeluaran = 0;
                $i = 0;
            @endphp
            @foreach ($kases as $index => $kas)
            @if ($kas->jenis_kas == 'pemasukan')
            @php
                $t_pemasukan += $kas->total;
                $saldo += $kas->total;
            @endphp 
            @else
            @php
                $t_pengeluaran += $kas->total;
                $saldo -= $kas->total
            @endphp 
            @endif
                <tr>
                    <td>{{ $i += 1  }}</td>
                    <td>{{str_replace('_',' ',$kas->tipe)}}</td>
                    <td>{{$kas->nama }}</td>
                    <td>{{date('d-m-Y',strtotime($kas->tanggal))}}</td>
                    <td>
                        @if ($kas->jenis_kas == 'pemasukan')
                            @currency2($kas->total)
                        @else
                        -
                        @endif
                    </td>
                    <td>
                        @if ($kas->jenis_kas == 'pengeluaran')
                        @currency2($kas->total)
                        @else
                        -
                        @endif
                    </td>
                    <td>
                        @currency2($saldo)
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="4" align="right">
                    <b>Total :</b>
                </td>
                <td>
                    @currency2($t_pemasukan)
                </td>
                <td>
                    @currency2($t_pengeluaran)
                </td>
                <td>
                    @currency2($saldo)
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