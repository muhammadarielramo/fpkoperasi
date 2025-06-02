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
        $loan = $this->loan;
        $totalCicilan = $loan->installments->sum('besar_ciclan')?? 0;

        $sisaPinjaman = $loan?->jumlah_pinjaman ? ($loan->jumlah_pinjaman - $totalCicilan) : null;

        return [
            'id' => $this->id,
            'installment' => [
                'tgl_pembayaran' => $this->tgl_pembayaran ?? null,
                'cicilan_ke' => $this->cicilan_ke ?? null,
                'nominal_pembayaran' => $this->besar_ciclan ?? null,
                'status' => $this->status ?? null
            ],
            'loan' => [
                'jumlah_pinjaman' => $loan->jumlah_pinjaman ?? null
            ],
            'sisa_pinjaman' => $sisaPinjaman
        ];
    }

    protected $installments = collect();
    public function withInstallments($installments) {
        $this->installments = $installments;
        return $this;
    }

    
}
