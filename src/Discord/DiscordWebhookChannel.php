<?php

namespace Gizburdt\Talk\Discord;

use Illuminate\Http\Client\RequestException;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class DiscordWebhookChannel
{
    /**
     * @throws RequestException
     */
    public function send(mixed $notifiable, Notification $notification): void
    {
        if (! $url = $notifiable->routeNotificationFor('discord', $notification)) {
            return;
        }

        Http::asJson()
            ->timeout(10)
            ->post($url, $notification->toDiscord($notifiable)->toArray())
            ->throw();
    }
}
