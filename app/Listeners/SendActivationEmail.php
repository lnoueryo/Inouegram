<?php

namespace App\Listeners;

use App\Events\UserActivationEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\ActivationEmail;
use Mail;
use Illuminate\Support\Facades\Hash;
class SendActivationEmail implements ShouldQueue
{

    public $connection = 'database';

    public $queue = 'default';

    public $delay = 5;
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
     * @param  UserActivationEmail  $event
     * @return void
     */
    public function handle(UserActivationEmail $event)
    {
        $event->user->password = Hash::make($event->user->password);
        $event->user->save();
    }
}
