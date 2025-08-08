<?php

namespace App\Events;

use App\Models\ThesisDocument;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ThesisStatusUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public ThesisDocument $document;
    public string $oldStatus;
    public string $newStatus;
    public ?User $reviewer;
    public array $metadata;

    /**
     * Create a new event instance.
     */
    public function __construct(
        ThesisDocument $document, 
        string $oldStatus, 
        string $newStatus, 
        ?User $reviewer = null, 
        array $metadata = []
    ) {
        $this->document = $document;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        $this->reviewer = $reviewer;
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
