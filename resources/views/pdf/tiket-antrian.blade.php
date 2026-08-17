<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tiket Antrian Pelayanan KB - {{ str_pad($antrian->nomor_antrian, 3, '0', STR_PAD_LEFT) }}</title>
    <style>
        @page {
            margin: 15mm 15mm;
            size: a5 portrait;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            font-size: 12px;
        }
        .ticket-box {
            border: 2px dashed #2563eb;
            border-radius: 12px;
            padding: 16px 20px;
            background-color: #ffffff;
        }
        .header {
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 12px;
            margin-bottom: 15px;
            text-align: center;
        }
        .header h1 {
            font-size: 16px;
            font-weight: bold;
            color: #0f172a;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header h2 {
            font-size: 13px;
            font-weight: 600;
            color: #2563eb;
            margin: 3px 0 0 0;
        }
        .header p {
            font-size: 10px;
            color: #64748b;
            margin: 3px 0 0 0;
        }
        .badge-status {
            display: inline-block;
            background-color: #dcfce7;
            color: #166534;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 10px;
            margin-top: 6px;
            border: 1px solid #bbf7d0;
        }
        .queue-number-box {
            background-color: #2563eb;
            color: #ffffff;
            border-radius: 10px;
            text-align: center;
            padding: 15px 10px;
            margin: 12px 0 16px 0;
        }
        .queue-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: bold;
            color: #bfdbfe;
            margin-bottom: 4px;
        }
        .queue-number {
            font-size: 48px;
            font-weight: 900;
            letter-spacing: 2px;
            line-height: 1;
        }
        .queue-sub {
            font-size: 9px;
            color: #dbeafe;
            margin-top: 6px;
        }
        .table-info {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .table-info td {
            padding: 6px 4px;
            font-size: 11px;
            vertical-align: top;
        }
        .table-info td.label {
            width: 32%;
            color: #64748b;
            font-weight: 500;
        }
        .table-info td.separator {
            width: 3%;
            color: #94a3b8;
        }
        .table-info td.value {
            width: 65%;
            color: #0f172a;
            font-weight: bold;
        }
        .instructions {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 10px;
            color: #475569;
            margin-bottom: 12px;
        }
        .instructions-title {
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 4px;
        }
        .instructions ol {
            margin: 0;
            padding-left: 16px;
        }
        .instructions li {
            margin-bottom: 2px;
        }
        .footer {
            border-top: 1px dashed #cbd5e1;
            padding-top: 10px;
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="ticket-box">
        <!-- Header -->
        <div class="header">
            <h1>Pemerintah Kabupaten Kolaka</h1>
            <h2>UPTD Puskesmas Wundulako</h2>
            <p>Sistem Informasi & Pelayanan Antrian KB Online</p>
            <div class="badge-status">TIKET TERKONFIRMASI ✓</div>
        </div>

        <!-- Big Queue Number -->
        <div class="queue-number-box">
            <div class="queue-label">Nomor Antrian Anda</div>
            <div class="queue-number">{{ str_pad($antrian->nomor_antrian, 3, '0', STR_PAD_LEFT) }}</div>
            <div class="queue-sub">Simpan dan bawa tiket ini saat mengunjungi Puskesmas</div>
        </div>

        <!-- Patient & Schedule Information Table -->
        <table class="table-info">
            <tr>
                <td class="label">Nama Pasien</td>
                <td class="separator">:</td>
                <td class="value">{{ $antrian->pesertaKb->nama_lengkap ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Nomor NIK</td>
                <td class="separator">:</td>
                <td class="value">{{ $antrian->pesertaKb->nik ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Nama Pasangan</td>
                <td class="separator">:</td>
                <td class="value">{{ $antrian->pesertaKb->nama_suami_istri ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Desa / Kelurahan</td>
                <td class="separator">:</td>
                <td class="value">{{ $antrian->pesertaKb->wilayah->nama_desa_kelurahan ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Jaminan Kesehatan</td>
                <td class="separator">:</td>
                <td class="value">{{ strtoupper($antrian->pesertaKb->penggunaan_asuransi ?? 'UMUM') }}</td>
            </tr>
            <tr>
                <td class="label">Jadwal Pelayanan</td>
                <td class="separator">:</td>
                <td class="value" style="color: #2563eb;">
                    @if($antrian->jadwalPelayanan)
                        {{ $antrian->jadwalPelayanan->tanggal->translatedFormat('l, d F Y') }}
                        ({{ substr($antrian->jadwalPelayanan->waktu_mulai, 0, 5) }} - {{ substr($antrian->jadwalPelayanan->waktu_selesai, 0, 5) }} WITA)
                    @else
                        -
                    @endif
                </td>
            </tr>
            @if($antrian->jadwalPelayanan && $antrian->jadwalPelayanan->keterangan)
            <tr>
                <td class="label">Keterangan Sesi</td>
                <td class="separator">:</td>
                <td class="value">{{ $antrian->jadwalPelayanan->keterangan }}</td>
            </tr>
            @endif
        </table>

        <!-- Instructions -->
        <div class="instructions">
            <div class="instructions-title">Petunjuk Kehadiran:</div>
            <ol>
                <li>Hadir di Loket Pelayanan KB Puskesmas Wundulako 15 menit sebelum sesi dimulai.</li>
                <li>Bawa KTP Asli dan Kartu BPJS/KIS (bila menggunakan asuransi).</li>
                <li>Tunjukkan lembar tiket PDF atau nomor antrian ini kepada petugas loket.</li>
            </ol>
        </div>

        <!-- Footer -->
        <div class="footer">
            Dicetak secara digital melalui SI-Pelayanan KB Puskesmas Wundulako • Waktu: {{ now()->translatedFormat('d F Y H:i') }} WITA
        </div>
    </div>
</body>
</html>
