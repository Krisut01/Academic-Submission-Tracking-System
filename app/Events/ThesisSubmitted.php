<?php

namespace App\Events;

use App\Models\ThesisDocument;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ThesisSubmitted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public ThesisDocument $document;
    public array $metadata;

    /**
     * Create a new event instance.
     */
    public function __construct(ThesisDocument $document, array $metadata = [])
    {
        $this->document = $document;
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
