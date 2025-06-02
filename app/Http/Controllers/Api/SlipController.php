<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Installment;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;

class SlipController extends Controller
{
    public function generate (Request $request) {
        $id = $request->id;
        $transaction = Transaction::with('installments.loan', 'member')->findOrFail($id);
        $installment = $transaction->installments->first();


        $loan = $installment->loan;
        $totalPaid = $loan?->installments->sum('besar_ciclan') ?? 0;
        $remainingDebt = $loan?->jumlah_pinjaman - $totalPaid;

        try {
            $pdf = Pdf::loadView('pdf.slip', compact('installment', 'remainingDebt'));

            // simpan ke storage
            $filename = 'slips/slip-' . $installment->id . '-' . now()->format('YmdHis') . '.pdf';
            Storage::disk('public')->put($filename, $pdf->output());

            // URL publik
            $publicUrl = asset('storage/' . $filename);

            return response()->json([
                'message' => 'Slip berhasil dibuat',
                'slip_url' => $publicUrl,
            ], 200);
        } catch ( \Exception $e) {
            return response()->json([
                'message' => 'Slip gagal dibuat',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
