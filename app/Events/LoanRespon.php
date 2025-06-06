<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Loan;
use App\Models\User;

class LoanRespon
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $loan;
    public $recipientUser;
    public $status;

    /**
     * Create a new event instance.
     * @param  \App\Models\Loan  $loan
     * @param  \App\Models\User  $recipientUser
     * @param  string  $status
     * @return void
     */
    public function __construct(Loan $loan, User $recipientUser, string $status)
    {
        $this->loan = $loan;
        $this->recipientUser = $recipientUser;
        $this->status = $status;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
