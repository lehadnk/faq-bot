<?php

namespace App\Services\Discord\API;

use Discord\Discord;

class DiscordApi
{
    /**
     * @var Discord
     */
    private $discord;

    public function __construct(string $token)
    {
        $this->discord = new Discord([
            'token' => $token
        ]);
    }

    public function setOnReadyHandler(callable $onReady)
    {
        $this->discord->on('ready', function (Discord $discord) use ($onReady) {
            $onReady($this);
        });
    }

    public function run()
    {
        $this->discord->run();
    }

    public function close()
    {
        $this->discord->close();
    }

    public function getDiscord(): Discord
    {
        return $this->discord;
    }

    public function emptyChannel(int $channelId): bool
    {
        $iterator = $this->discord->getChannel($channelId)->messages->getIterator();
        foreach ($iterator as $message) {
            $message->delete();
        }

        return true;
    }
}
