<?php

namespace App\Listeners;

use App\Events\SeenAdminEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SeenAdminEventListener
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
     * @param  object  $event
     * @return void
     */
    public function handle(SeenAdminEvent $event)
    {
        $seen_fields = [
            'seen_admin_id' => $event->user_id,
            'seen_at' => now(),
        ];
        $event->model->update($seen_fields);
    }
}
