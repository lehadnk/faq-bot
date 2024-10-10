<?php

namespace App\Services\DiscordChannel;

use App\Services\DiscordChannel\Exceptions\NoChannelException;
use Discord\Discord;
use Discord\Helpers\Collection;
use Psr\Log\LoggerInterface;

class DiscordChannelService
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function emptyChannel(Discord $discord, string $channelId)
    {
        $channel = $discord->getChannel($channelId);
        if (!$channel) {
            $message = "Attempt to clean channel $channelId: no such channel";

            echo $message.PHP_EOL;
            $this->logger->error($message);

            throw new NoChannelException();
        }
        $channel->getMessageHistory(['cache' => false])->done(function(Collection $messages) use ($channel) {
            $channel->deleteMessages($messages);
        });

        return true;
    }
}
