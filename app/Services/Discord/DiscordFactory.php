<?php

namespace App\Services\Discord;

use App\Services\Discord\API\DiscordApi;

class DiscordFactory
{
    public function getDiscordApi(): DiscordApi
    {
        return new DiscordApi(
            env('DISCORD_API_TOKEN')
        );
    }
}
