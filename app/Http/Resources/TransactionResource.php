<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'member_name' => $this->member->user->name,
            'tgl_transaski' => $this->tgl_transaksi,
            'jumlah' => $this->jumlah,
            'address' => $this->member->address,
            'id_loan' => $this->id_loan
        ];
    }
}
