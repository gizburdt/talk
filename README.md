# Talk

[![Latest Version on Packagist](https://img.shields.io/packagist/v/gizburdt/talk.svg?style=flat-square)](https://packagist.org/packages/gizburdt/talk)
[![Total Downloads](https://img.shields.io/packagist/dt/gizburdt/talk.svg?style=flat-square)](https://packagist.org/packages/gizburdt/talk)
[![Tests](https://img.shields.io/github/actions/workflow/status/gizburdt/talk/tests.yml?branch=master&label=tests&style=flat-square)](https://github.com/gizburdt/talk/actions/workflows/tests.yml)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

Send notifications to recipients

## Installation

You can install the package via composer:

``` bash
composer require gizburdt/talk
```

## Usage

### Discord

Create a webhook in your Discord channel settings. Return `discord` from `via()` and add a `toDiscord()` method to your notification:

``` php
use Gizburdt\Talk\Discord\DiscordEmbed;
use Gizburdt\Talk\Discord\DiscordMessage;

public function via(object $notifiable): array
{
    return ['discord'];
}

public function toDiscord(object $notifiable): DiscordMessage
{
    return DiscordMessage::make('Deployment finished')
        ->username('Deploy bot')
        ->embed(fn (DiscordEmbed $embed) => $embed
            ->title('Production')
            ->color('#22c55e')
            ->field('Duration', '42s', inline: true));
}
```

Return the webhook URL from `routeNotificationFor('discord')` on your notifiable:

``` php
public function routeNotificationForDiscord(): string
{
    return $this->discord_webhook_url;
}
```

On-demand notifications work too:

``` php
Notification::route('discord', $webhookUrl)->notify(new Deployed);
```

A failed request (for example an invalid payload) throws an `Illuminate\Http\Client\RequestException`.

## Testing

``` bash
composer test
```
