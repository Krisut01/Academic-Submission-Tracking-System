<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserRoleChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public string $oldRole;
    public string $newRole;
    public ?User $changedBy;
    public array $metadata;

    /**
     * Create a new event instance.
     */
    public function __construct(
        User $user, 
        string $oldRole, 
        string $newRole, 
        ?User $changedBy = null, 
        array $metadata = []
    ) {
        $this->user = $user;
        $this->oldRole = $oldRole;
        $this->newRole = $newRole;
        $this->changedBy = $changedBy;
        $this->metadata = $metadata;
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
