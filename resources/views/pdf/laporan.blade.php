<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>
        @if($activeTab === 'pelayanan')
            Laporan Rekapitulasi Pelayanan KB
        @elseif($activeTab === 'peserta')
            Laporan Data Peserta KB
        @else
            Laporan Inventaris Alokon
        @endif
    </title>
    <style>
        @page {
            margin: 12mm 12mm 15mm 12mm;
            size: a4 landscape;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #111827;
            line-height: 1.3;
            margin: 0;
            padding: 0;
            font-size: 10px;
            background-color: #ffffff;
        }
        
        /* Kop Surat */
        .kop-header {
            text-align: center;
            border-bottom: 3px double #000000;
            padding-bottom: 6px;
            margin-bottom: 12px;
        }
        .kop-header .instansi-prov {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
            color: #000000;
        }
        .kop-header .instansi-dinas {
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 2px 0 0 0;
            color: #000000;
        }
        .kop-header .instansi-pkm {
            font-size: 15px;
            font-weight: 900;
            text-transform: uppercase;
            margin: 2px 0 0 0;
            color: #000000;
        }
        .kop-header .instansi-alamat {
            font-size: 9px;
            color: #374151;
            margin: 3px 0 0 0;
        }

        /* Judul Laporan */
        .report-title {
            text-align: center;
            margin-bottom: 12px;
        }
        .report-title h2 {
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
            margin: 0;
            letter-spacing: 0.5px;
        }
        .report-meta {
            margin-top: 4px;
            font-size: 10px;
            color: #1f2937;
        }
        .report-meta span {
            margin: 0 8px;
        }

        /* Summary Box */
        .summary-box {
            border: 1px solid #000000;
            background-color: #f9fafb;
            padding: 6px 10px;
            margin-bottom: 12px;
        }
        .summary-title {
            font-weight: bold;
            font-size: 9px;
            text-transform: uppercase;
            margin-bottom: 4px;
            color: #374151;
        }
        .summary-grid {
            width: 100%;
            border-collapse: collapse;
        }
        .summary-grid td {
            font-size: 9.5px;
            padding: 2px 6px;
            border-right: 1px solid #d1d5db;
            text-align: center;
        }
        .summary-grid td:last-child {
            border-right: none;
        }
        .summary-grid td .label {
            color: #4b5563;
            font-size: 8.5px;
            display: block;
        }
        .summary-grid td .val {
            font-weight: bold;
            font-size: 11px;
            color: #111827;
        }

        /* Data Table */
        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 9px;
        }
        .table-data th {
            background-color: #f3f4f6;
            color: #111827;
            font-weight: bold;
            text-align: left;
            padding: 5px 6px;
            border: 1px solid #000000;
            text-transform: uppercase;
            font-size: 8.5px;
        }
        .table-data td {
            padding: 4px 6px;
            border: 1px solid #000000;
            vertical-align: middle;
        }
        .table-data tr:nth-child(even) {
            background-color: #fafafa;
        }
        .text-center {
            text-align: center !important;
        }
        .text-right {
            text-align: right !important;
        }
        .font-mono {
            font-family: monospace;
        }
        .font-bold {
            font-weight: bold;
        }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 1px 4px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }
        .badge-green {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        .badge-amber {
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }
        .badge-rose {
            background-color: #ffe4e6;
            color: #9f1239;
            border: 1px solid #fecdd3;
        }

        /* Signatures Section */
        .signature-section {
            width: 100%;
            margin-top: 15px;
            page-break-inside: avoid;
        }
        .sig-table {
            width: 100%;
            border-collapse: collapse;
        }
        .sig-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            font-size: 9.5px;
            padding: 0 20px;
        }
        .sig-space {
            height: 55px;
        }
        .sig-name {
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
        }

        .footer-note {
            margin-top: 12px;
            border-top: 1px dashed #9ca3af;
            padding-top: 4px;
            font-size: 8px;
            color: #6b7280;
            font-style: italic;
            text-align: right;
        }
    </style>
</head>
<body>

    <!-- Kop Surat Resmi -->
    <div class="kop-header">
        <div class="instansi-prov">PEMERINTAH KABUPATEN KOLAKA</div>
        <div class="instansi-dinas">DINAS PENGENDALIAN PENDUDUK DAN KELUARGA BERENCANA</div>
        <div class="instansi-pkm">UPTD PUSKESMAS KECAMATAN WUNDULAKO</div>
        <div class="instansi-alamat">
            Jl. Poros Kolaka - Pomalaa, Kec. Wundulako, Kab. Kolaka, Sulawesi Tenggara 93561<br>
            Email: pkm.wundulako@kolakakab.go.id | Sistem Informasi Pelayanan KB
        </div>
    </div>

    <!-- Judul Laporan & Periode -->
    <div class="report-title">
        <h2>
            @if($activeTab === 'pelayanan')
                LAPORAN REKAPITULASI PELAYANAN KELUARGA BERENCANA (KB)
            @elseif($activeTab === 'peserta')
                LAPORAN REKAPITULASI DATA PESERTA KB
            @else
                LAPORAN STATUS INVENTARIS ALAT & OBAT KONTRASEPSI (ALOKON)
            @endif
        </h2>
        <div class="report-meta">
            <span><b>Periode:</b> {{ \Carbon\Carbon::parse($dariTanggal)->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($sampaiTanggal)->translatedFormat('d F Y') }}</span>
            <span>•</span>
            <span><b>Wilayah:</b> {{ $wilayahSelected?->nama_desa_kelurahan ?? 'Semua Wilayah (Kec. Wundulako)' }}</span>
            @if($alokonSelected && $activeTab !== 'peserta')
                <span>•</span>
                <span><b>Alokon:</b> {{ $alokonSelected->nama_alokon }}</span>
            @endif
        </div>
    </div>

    <!-- Summary Box -->
    <div class="summary-box">
        <div class="summary-title">Ringkasan Data Laporan:</div>
        <table class="summary-grid">
            <tr>
                @if($activeTab === 'pelayanan')
                    <td>
                        <span class="label">Total Pelayanan:</span>
                        <span class="val">{{ $totalPelayanan }} tindakan</span>
                    </td>
                    <td>
                        <span class="label">Peserta Terlayani:</span>
                        <span class="val">{{ $totalPesertaDilayani }} orang</span>
                    </td>
                    <td>
                        <span class="label">Alokon Terdistribusi:</span>
                        <span class="val">{{ $totalAlokonTerdistribusi }} unit</span>
                    </td>
                    <td>
                        <span class="label">Cakupan Wilayah:</span>
                        <span class="val">{{ $totalWilayahTercakup }} desa/kelurahan</span>
                    </td>
                @elseif($activeTab === 'peserta')
                    <td>
                        <span class="label">Total Peserta:</span>
                        <span class="val">{{ $totalPesertaTerdaftar }} orang</span>
                    </td>
                    <td>
                        <span class="label">Terverifikasi:</span>
                        <span class="val">{{ $totalPesertaTerverifikasi }} orang</span>
                    </td>
                    <td>
                        <span class="label">Menunggu Verifikasi:</span>
                        <span class="val">{{ $totalPesertaTerdaftar - $totalPesertaTerverifikasi }} orang</span>
                    </td>
                    <td>
                        <span class="label">Sebaran Wilayah:</span>
                        <span class="val">{{ $pesertas->pluck('wilayah_id')->unique()->count() }} desa/kelurahan</span>
                    </td>
                @else
                    <td>
                        <span class="label">Varian Alokon:</span>
                        <span class="val">{{ $inventory->count() }} jenis</span>
                    </td>
                    <td>
                        <span class="label">Sisa Stok Total:</span>
                        <span class="val">{{ $totalStokTersedia }} unit</span>
                    </td>
                    <td>
                        <span class="label">Stok Kritis (< 5):</span>
                        <span class="val">{{ $inventory->where('stok', '<', 5)->count() }} jenis</span>
                    </td>
                    <td>
                        <span class="label">Total Terdistribusi:</span>
                        <span class="val">{{ $inventory->sum('pelayanans_count') }} unit</span>
                    </td>
                @endif
            </tr>
        </table>
    </div>

    <!-- Data Tables -->
    @if($activeTab === 'pelayanan')
        <table class="table-data">
            <thead>
                <tr>
                    <th class="text-center" style="width: 25px;">No</th>
                    <th style="width: 65px;">Tanggal</th>
                    <th style="width: 140px;">Nama Peserta (NIK)</th>
                    <th style="width: 90px;">Pasangan</th>
                    <th style="width: 95px;">Wilayah</th>
                    <th style="width: 110px;">Alokon / Faskes</th>
                    <th style="width: 75px;">Tindakan</th>
                    <th class="text-center" style="width: 65px;">Skrining</th>
                    <th class="text-center" style="width: 65px;">Consent</th>
                    <th class="text-center" style="width: 70px;">Kunj. Ulang</th>
                    <th style="width: 85px;">Petugas</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pelayanans as $index => $p)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $p->tanggal_pelayanan ? $p->tanggal_pelayanan->translatedFormat('d/m/Y') : '-' }}</td>
                        <td>
                            <b>{{ $p->pesertaKb?->nama_lengkap ?? '-' }}</b><br>
                            <span class="font-mono" style="font-size: 8px; color: #4b5563;">NIK: {{ $p->pesertaKb?->nik ?? '-' }}</span>
                        </td>
                        <td>{{ $p->pesertaKb?->nama_suami_istri ?? '-' }}</td>
                        <td>{{ $p->pesertaKb?->wilayah?->nama_desa_kelurahan ?? '-' }}</td>
                        <td>
                            <b>{{ $p->alokon?->nama_alokon ?? '-' }}</b><br>
                            <span style="font-size: 7.5px; color: #6b7280;">{{ $p->alokon?->instansi?->nama_instansi ?? '-' }}</span>
                        </td>
                        <td style="text-transform: capitalize;">{{ $p->skriningMedis?->informedConsent?->jenis_tindakan_medis ?? '-' }}</td>
                        <td class="text-center">
                            @if($p->skriningMedis?->adaRiwayatPenyakit())
                                <span class="badge badge-rose">Beresiko</span>
                            @else
                                <span class="badge badge-green">Lolos</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($p->skriningMedis?->informedConsent?->isLengkap())
                                <span class="badge badge-green">Lengkap</span>
                            @else
                                <span class="badge badge-amber">Belum</span>
                            @endif
                        </td>
                        <td class="text-center font-mono">{{ $p->tanggal_kunjungan_ulang ? $p->tanggal_kunjungan_ulang->translatedFormat('d/m/Y') : '-' }}</td>
                        <td>{{ $p->penanggung_jawab_nama ?: 'Petugas Faskes' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center" style="padding: 15px; color: #6b7280;">
                            Tidak ada data pelayanan untuk filter yang dipilih.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @elseif($activeTab === 'peserta')
        <table class="table-data">
            <thead>
                <tr>
                    <th class="text-center" style="width: 25px;">No</th>
                    <th style="width: 140px;">Nama Peserta (NIK)</th>
                    <th style="width: 95px;">Nama Pasangan</th>
                    <th style="width: 80px;">No. HP/WA</th>
                    <th style="width: 75px;">Tgl Lahir/Usia</th>
                    <th style="width: 130px;">Wilayah & Alamat</th>
                    <th style="width: 55px;">Asuransi</th>
                    <th style="width: 75px;">Status KB</th>
                    <th class="text-center" style="width: 60px;">Anak</th>
                    <th class="text-center" style="width: 65px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesertas as $index => $p)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            <b>{{ $p->nama_lengkap }}</b><br>
                            <span class="font-mono" style="font-size: 8px; color: #4b5563;">NIK: {{ $p->nik }}</span>
                        </td>
                        <td>{{ $p->nama_suami_istri }}</td>
                        <td class="font-mono">{{ $p->nomor_hp ?: '-' }}</td>
                        <td>
                            {{ $p->tanggal_lahir_istri ? $p->tanggal_lahir_istri->translatedFormat('d/m/Y') : '-' }}
                            ({{ $p->tanggal_lahir_istri ? $p->tanggal_lahir_istri->age : 0 }} th)
                        </td>
                        <td>
                            <b>{{ $p->wilayah?->nama_desa_kelurahan ?? '-' }}</b><br>
                            <span style="font-size: 7.5px; color: #6b7280;">{{ $p->alamat_lengkap }}</span>
                        </td>
                        <td style="text-transform: uppercase;"><b>{{ $p->penggunaan_asuransi ?? '-' }}</b></td>
                        <td style="text-transform: capitalize;">{{ $p->status_kepesertaan ? str_replace('_', ' ', $p->status_kepesertaan) : '-' }}</td>
                        <td class="text-center">
                            <b>{{ $p->jumlah_anak_hidup ?? 0 }}</b> (L:{{ $p->jumlah_anak_laki ?? 0 }} P:{{ $p->jumlah_anak_perempuan ?? 0 }})
                        </td>
                        <td class="text-center">
                            @if($p->isTerverifikasi())
                                <span class="badge badge-green">Terverifikasi</span>
                            @else
                                <span class="badge badge-amber">Menunggu</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center" style="padding: 15px; color: #6b7280;">
                            Tidak ada data peserta untuk filter yang dipilih.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @else
        <table class="table-data">
            <thead>
                <tr>
                    <th class="text-center" style="width: 30px;">No</th>
                    <th>Nama Alat / Obat Kontrasepsi</th>
                    <th>Faskes / Instansi</th>
                    <th class="text-center" style="width: 100px;">Kode Faskes</th>
                    <th class="text-right" style="width: 100px;">Sisa Stok (Unit)</th>
                    <th class="text-right" style="width: 120px;">Total Terdistribusi</th>
                    <th class="text-center" style="width: 100px;">Status Ketersediaan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inventory as $index => $a)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td><b>{{ $a->nama_alokon }}</b></td>
                        <td>{{ $a->instansi?->nama_instansi ?? '-' }}</td>
                        <td class="text-center font-mono">{{ $a->instansi?->kode_faskes ?? '-' }}</td>
                        <td class="text-right font-mono font-bold">{{ $a->stok }} unit</td>
                        <td class="text-right font-mono">{{ $a->pelayanans_count ?? 0 }} unit</td>
                        <td class="text-center">
                            @if($a->stok < 5)
                                <span class="badge badge-rose">KRITIS</span>
                            @elseif($a->stok < 10)
                                <span class="badge badge-amber">RENDAH</span>
                            @else
                                <span class="badge badge-green">AMAN</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center" style="padding: 15px; color: #6b7280;">
                            Tidak ada data inventaris alokon.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endif

    <!-- Lembar Pengesahan Tanda Tangan -->
    <div class="signature-section">
        <table class="sig-table">
            <tr>
                <td>
                    <div>Mengetahui,</div>
                    <div class="font-bold">Kepala UPTD Puskesmas Wundulako</div>
                    <div class="sig-space"></div>
                    <div class="sig-name">( .................................................... )</div>
                    <div>NIP. ....................................................</div>
                </td>
                <td>
                    <div>Wundulako, {{ now()->translatedFormat('d F Y') }}</div>
                    <div class="font-bold">Penanggung Jawab / Bidan Koordinator</div>
                    <div class="sig-space"></div>
                    <div class="sig-name">{{ auth()->user()->name ?? '( .................................................... )' }}</div>
                    <div>NIP. ....................................................</div>
                </td>
            </tr>
        </table>
        <div class="footer-note">
            Dokumen ini diunduh secara digital dari Sistem Informasi Pelayanan KB Kecamatan Wundulako pada {{ now()->translatedFormat('l, d F Y H:i:s') }} WITA
        </div>
    </div>

</body>
</html>
