<?php

namespace App\Listeners;

use App\Events\LoanRespon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Notification;

class SendLoanRespondNotification implements ShouldQueue
{
    use InteractsWithQueue;
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     * *@param  \App\Events\LoanApproved  $event
     * @return void
     */
    public function handle(LoanRespon $event): void
    {
        $loan = $event->loan;
        $recipientUser = $event->recipientUser;
        $status = $event->status; // Ambil status dari event

        $title = '';
        $message = '';
        $type = ''; // Tipe notifikasi di database

        switch ($status) {
            case 'approved':
                $title = 'Pengajuan Pinjaman Diterima!';
                $message = "Pengajuan pinjaman Anda sebesar Rp " . number_format($loan->jumlah_pinjaman, 0, ',', '.') . " telah disetujui. Silakan cek detail pinjaman Anda.";
                $type = 'loan_approved';
                break;
            case 'rejected':
                $title = 'Pengajuan Pinjaman Ditolak.';
                $message = "Maaf, pengajuan pinjaman Anda sebesar Rp " . number_format($loan->jumlah_pinjaman, 0, ',', '.') . " telah ditolak. Silakan hubungi admin untuk informasi lebih lanjut.";
                $type = 'loan_rejected';
                break;
            // Anda bisa menambahkan case lain jika ada status pinjaman lain (misal: 'pending_review', 'disbursed')
            default:
                // Fallback atau log jika status tidak dikenali
                $title = 'Pembaruan Status Pinjaman';
                $message = 'Status pinjaman Anda telah diperbarui menjadi: ' . $status;
                $type = 'loan_status_update';
                break;
        }

        try {
            Notification::create([
                'id_user' => $recipientUser->id,
                'type'    => $type,
                'title'   => $title,
                'message' => $message,
                'data'    => json_encode(['loan_id' => $loan->id, 'amount' => $loan->jumlah_pinjaman, 'installment' => $loan->installment, 'status' => $status]),
            ]);
        } catch (\Exception $e) {
            dd($e);
        }

    }
}
