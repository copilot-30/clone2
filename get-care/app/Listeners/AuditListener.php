<?php

namespace App\Listeners;

use App\Events\AuditableEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\AuditLog;

class AuditListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AuditableEvent  $event
     * @return void
     */
    public function handle(AuditableEvent $event)
    {
        AuditLog::create([
            'user_id' => $event->userId,
            'event_type' => $event->eventType,
            'auditable_id' => $event->auditableId,
            'auditable_type' => $event->auditableType,
            'old_values' => $event->oldValues,
            'new_values' => $event->newValues,
            'url' => $event->url,
            'ip_address' => $event->ipAddress,
            'user_agent' => $event->userAgent,
            'tags' => $event->tags,
        ]);
    }
}
