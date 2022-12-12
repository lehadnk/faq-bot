<?php

namespace App\Services\Glossary\Business;

use Discord\Parts\Channel\Message;

class MessageUrlBuilder
{
    public function getMessageJumpUrl(Message $message): string
    {
        return "https://discord.com/channels/{$message->channel->guild_id}/{$message->channel_id}/{$message->id}";
    }
}
