<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan Kas HIMSU</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
        }
        .kop-surat {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .kop-surat h2, .kop-surat h3, .kop-surat p {
            margin: 2px 0;
        }
        .judul-laporan {
            text-align: center;
            margin-bottom: 15px;
        }
        .judul-laporan h3 {
            margin: 0;
            text-transform: uppercase;
        }
        .judul-laporan p {
            margin: 5px 0 0 0;
            font-style: italic;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            border: 1px solid #999;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #f2f2f2;
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .font-bold {
            font-weight: bold;
        }
        .tanda-tangan {
            width: 100%;
            margin-top: 40px;
        }
        .tanda-tangan td {
            border: none;
            text-align: center;
            padding: 0;
        }
        .ttd-space {
            height: 80px;
        }
    </style>
</head>
<body>

    <div class="kop-surat">
        <h2>HIMPUNAN MAHASISWA SUMATERA UTARA</h2>
        <h3>(HIMSU) BENGKALIS</h3>
        <p>Sekretariat: Jl. Kelapapati Laut, Kabupaten Bengkalis, Riau</p>
    </div>

    <div class="judul-laporan">
        <h3>Laporan Arus Keuangan Kas</h3>
        <p>Periode: {{ $periode }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Tanggal</th>
                <th width="40%">Uraian Keterangan</th>
                <th width="20%">Pemasukan (Rp)</th>
                <th width="20%">Pengeluaran (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporan as $key => $row)
            <tr>
                <td class="text-center">{{ $key + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($row->tanggal)->translatedFormat('d M Y') }}</td>
                <td>
                    <span class="font-bold">{{ $row->kategori }}</span><br>
                    <span style="font-size: 10px; color: #666;">{{ $row->keterangan ?? '-' }}</span>
                </td>
                <td class="text-right">
                    {{ $row->jenis_transaksi == 'pemasukan' ? number_format($row->nominal, 0, ',', '.') : '-' }}
                </td>
                <td class="text-right">
                    {{ $row->jenis_transaksi == 'pengeluaran' ? number_format($row->nominal, 0, ',', '.') : '-' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Tidak ada transaksi pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-right font-bold" style="background-color: #f2f2f2;">TOTAL TRANSAKSI:</td>
                <td class="text-right font-bold" style="background-color: #f2f2f2;">{{ number_format($totalPemasukan, 0, ',', '.') }}</td>
                <td class="text-right font-bold" style="background-color: #f2f2f2;">{{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="3" class="text-right font-bold" style="background-color: #e6f7ff;">SALDO AKHIR KAS:</td>
                <td colspan="2" class="text-right font-bold" style="background-color: #e6f7ff; font-size: 14px;">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <table class="tanda-tangan">
        <tr>
            <td width="50%">
                <p>Mengetahui,</p>
                <p><strong>Ketua Umum HIMSU</strong></p>
                <div class="ttd-space"></div>
                <p>_______________________</p>
            </td>
            <td width="50%">
                <p>Bengkalis, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                <p><strong>Bendahara / Admin Kas</strong></p>
                <div class="ttd-space"></div>
                <p>_______________________</p>
            </td>
        </tr>
    </table>

</body>
</html>