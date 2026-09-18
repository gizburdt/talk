<?php

use Gizburdt\Talk\Discord\DiscordEmbed;
use Gizburdt\Talk\Discord\DiscordMessage;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\RequestException;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification as NotificationFacade;

class TestNotification extends Notification
{
    public function via(mixed $notifiable): array
    {
        return ['discord'];
    }

    public function toDiscord(mixed $notifiable): DiscordMessage
    {
        return DiscordMessage::make('Hello')->username('Talk');
    }
}

it('builds a message', function () {
    $message = DiscordMessage::make('Hello')
        ->username('Talk')
        ->avatar('https://example.com/a.png')
        ->tts()
        ->embed(fn (DiscordEmbed $embed) => $embed->title('Title')->color('#ff0000'));

    expect($message->toArray())->toBe([
        'content' => 'Hello',
        'username' => 'Talk',
        'avatar_url' => 'https://example.com/a.png',
        'tts' => true,
        'embeds' => [['title' => 'Title', 'color' => 16711680]],
    ]);
});

it('omits empty values', function () {
    expect(DiscordMessage::make('Hello')->toArray())->toBe(['content' => 'Hello']);
});

it('builds an embed', function () {
    $embed = DiscordEmbed::make()
        ->title('Title')
        ->field('Name', 'Value', true)
        ->footer('Footer')
        ->timestamp(new DateTimeImmutable('2026-01-01T00:00:00+00:00'));

    expect($embed->toArray())->toBe([
        'title' => 'Title',
        'footer' => ['text' => 'Footer'],
        'timestamp' => '2026-01-01T00:00:00+00:00',
        'fields' => [['name' => 'Name', 'value' => 'Value', 'inline' => true]],
    ]);
});

it('sends to an on-demand webhook url', function () {
    Http::fake();

    NotificationFacade::route('discord', 'https://discord.test/webhook')
        ->notifyNow(new TestNotification);

    Http::assertSent(fn (Request $request) => $request->url() === 'https://discord.test/webhook'
        && $request['content'] === 'Hello'
        && $request['username'] === 'Talk');
});

it('does not send without a webhook url', function () {
    Http::fake();

    NotificationFacade::route('discord', null)->notifyNow(new TestNotification);

    Http::assertNothingSent();
});

it('throws when discord rejects the request', function () {
    Http::fake(['*' => Http::response(['message' => 'Invalid'], 400)]);

    NotificationFacade::route('discord', 'https://discord.test/webhook')
        ->notifyNow(new TestNotification);
})->throws(RequestException::class);
