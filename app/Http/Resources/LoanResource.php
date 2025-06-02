<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'nominal' => $this->jumlah_pinjaman,
            'tanggal_pengajuan' => $this->tgl_pengajuan,
            'jangka_waktu' => $this->tenor,
            'tgl_persetujuan' => $this->tgl_persetujuan,
            'status' => $this->status
        ];
    }
}
