<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InstallmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $transaction = $this->transaction;
        $loan = $transaction?->loan;
        $location = $transaction?->location;

        $loanAmount = $loan?->jumlah_pinjaman ?? 0;

        $totalPaid = \App\Models\Installment::whereHas('transaction',
                    function ($query) use ($loan) {
                    $query->where('id_loan', $loan->id);
        })->sum('besar_ciclan');

        $sisaHutang = max(0, $loanAmount - $totalPaid);


    return [
        'jumlah_pinjaman' => $loan ? $loan->jumlah_pinjaman : null,
        'nominal_pembayaran' => $this->besar_ciclan,
        'tanggal_transaksi' => $transaction?->tgl_transaksi,
        'waktu_transaksi' => optional($transaction?->created_at)->format('H:i:s'),
        'angsuran_ke' => 'Ke-' . $this->cicilan_ke,
        'sisa_hutang' => $sisaHutang,
        'status_pembayaran' => $this->status, // misal: 'Tepat Waktu', 'Terlambat'
        'lokasi' => [
            'nama' => $location?->nama_lokasi,
            'koordinat' => $location ? ['lat' => $location->lat, 'lng' => $location->lng] : null,
            'link_maps' => $location ? 'https://maps.google.com/?q=' . $location->lat . ',' . $location->lng : null
        ]
    ];
    }


}
