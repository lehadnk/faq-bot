<?php

namespace App\Services\DiscordChannel;

use App\Services\DiscordChannel\Exceptions\NoChannelException;
use Discord\Discord;
use Discord\Helpers\Collection;

class DiscordChannelFacade
{
    public function emptyChannel(Discord $discord, string $channelId)
    {
        $channel = $discord->getChannel($channelId);
        if (!$channel) {
            throw new NoChannelException();
        }

        $channel->getMessageHistory(['cache' => false])->done(function(Collection $messages) use ($channel) {
            $channel->deleteMessages($messages);
        });

        return true;
    }
}
