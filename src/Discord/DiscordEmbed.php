<?php

namespace Gizburdt\Talk\Discord;

use DateTimeInterface;
use Illuminate\Contracts\Support\Arrayable;

class DiscordEmbed implements Arrayable
{
    /**
     * @param  array<int, array{name: string, value: string, inline: bool}>  $fields
     * @param  array<string, mixed>  $attributes
     */
    public function __construct(
        protected array $attributes = [],
        protected array $fields = [],
    ) {}

    public static function make(): static
    {
        return new static;
    }

    public function title(string $title): static
    {
        return $this->set('title', $title);
    }

    public function description(string $description): static
    {
        return $this->set('description', $description);
    }

    public function url(string $url): static
    {
        return $this->set('url', $url);
    }

    public function color(int|string $color): static
    {
        $color = is_string($color) ? hexdec(ltrim($color, '#')) : $color;

        return $this->set('color', $color);
    }

    public function timestamp(DateTimeInterface $timestamp): static
    {
        return $this->set('timestamp', $timestamp->format(DateTimeInterface::ATOM));
    }

    public function author(string $name, ?string $url = null, ?string $iconUrl = null): static
    {
        return $this->set('author', array_filter([
            'name' => $name,
            'url' => $url,
            'icon_url' => $iconUrl,
        ]));
    }

    public function footer(string $text, ?string $iconUrl = null): static
    {
        return $this->set('footer', array_filter([
            'text' => $text,
            'icon_url' => $iconUrl,
        ]));
    }

    public function thumbnail(string $url): static
    {
        return $this->set('thumbnail', ['url' => $url]);
    }

    public function image(string $url): static
    {
        return $this->set('image', ['url' => $url]);
    }

    public function field(string $name, string $value, bool $inline = false): static
    {
        $this->fields[] = ['name' => $name, 'value' => $value, 'inline' => $inline];

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([...$this->attributes, 'fields' => $this->fields]);
    }

    protected function set(string $key, mixed $value): static
    {
        $this->attributes[$key] = $value;

        return $this;
    }
}
