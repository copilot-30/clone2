<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AuditableEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
public $userId;
public $eventType;
public $auditableId;
public $auditableType;
public $oldValues;
public $newValues;
public $url;
public $ipAddress;
public $userAgent;
public $tags;

public function __construct($userId, $eventType, $data = [])
{
    $this->userId = $userId;
    $this->eventType = $eventType;
    $this->auditableId = $data['auditable_id'] ?? null;
    $this->auditableType = $data['auditable_type'] ?? null;
    $this->oldValues = $data['old_values'] ?? null;
    $this->newValues = $data['new_values'] ?? null;
    $this->url = $data['url'] ?? request()->fullUrl();
    $this->ipAddress = $data['ip_address'] ?? request()->ip();
    $this->userAgent = $data['user_agent'] ?? request()->userAgent();
    $this->tags = $data['tags'] ?? null;
}
}

