<?php

namespace App\Services\Emoji\Dto;

class EmojiStorageDto
{
    private array $emojis = [];

    public function store(string $name, string $code)
    {
        $this->emojis[$name] = $code;
    }

    public function getByName(string $name): ?string
    {
        if (!isset($this->emojis[$name])) {
            return null;
        }

        return $this->emojis[$name];
    }
}
