<?php

namespace Gizburdt\Talk;

use Gizburdt\Talk\Discord\DiscordChannel;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class TalkServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Notification::resolved(fn (ChannelManager $manager) => $manager->extend(
            'discord',
            fn ($app) => $app->make(DiscordChannel::class)
        ));
    }
}
