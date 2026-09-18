<?php

namespace Gizburdt\Talk\Discord;

use Illuminate\Contracts\Support\Arrayable;

class DiscordMessage implements Arrayable
{
    /**
     * @param  array<int, DiscordEmbed>  $embeds
     */
    public function __construct(
        protected ?string $content = null,
        protected ?string $username = null,
        protected ?string $avatarUrl = null,
        protected bool $tts = false,
        protected array $embeds = [],
    ) {}

    public static function make(?string $content = null): static
    {
        return new static($content);
    }

    public function content(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function username(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function avatar(string $url): static
    {
        $this->avatarUrl = $url;

        return $this;
    }

    public function tts(bool $tts = true): static
    {
        $this->tts = $tts;

        return $this;
    }

    public function embed(DiscordEmbed|callable $embed): static
    {
        if (is_callable($embed)) {
            $embed = $embed(DiscordEmbed::make());
        }

        $this->embeds[] = $embed;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'content' => $this->content,
            'username' => $this->username,
            'avatar_url' => $this->avatarUrl,
            'tts' => $this->tts,
            'embeds' => array_map(fn (DiscordEmbed $embed) => $embed->toArray(), $this->embeds),
        ]);
    }
}
