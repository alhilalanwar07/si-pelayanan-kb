<?php

namespace App\Http\Controllers;

use App\Models\AntrianJadwal;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class TiketAntrianPdfController extends Controller
{
    /**
     * Download PDF Tiket Antrian Resmi
     */
    public function download(AntrianJadwal $antrian)
    {
        $antrian->load(['pesertaKb.wilayah', 'jadwalPelayanan']);

        $pdf = Pdf::loadView('pdf.tiket-antrian', compact('antrian'));
        $pdf->setPaper('a5', 'portrait');

        $filename = 'Tiket-Antrian-KB-' . str_pad($antrian->nomor_antrian, 3, '0', STR_PAD_LEFT) . '-' . ($antrian->pesertaKb->nik ?? 'pasien') . '.pdf';

        return $pdf->download($filename);
    }
}
