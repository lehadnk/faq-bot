<?php

namespace App\Services\Glossary\Business;

use App\Models\Question;
use Discord\Discord;
use Discord\Parts\Channel\Channel;
use Discord\Parts\Channel\Message;
use Discord\Parts\Embed\Embed;

class GlossaryPostProcessor
{
    private int $unprocessedTasks;
    private MessageUrlBuilder $messageUrlBuilder;
    private Discord $discord;
    private array $messageUrls = [];
    private Channel $channel;

    public function __construct(
        Discord $discord,
        Channel $channel,
        MessageUrlBuilder $messageUrlBuilder,
        int $taskCount
    ) {
        $this->messageUrlBuilder = $messageUrlBuilder;
        $this->unprocessedTasks = $taskCount;
        $this->discord = $discord;
        $this->channel = $channel;
    }

    public function onMessageProcessed(Message $message, Question $question)
    {
        $this->messageUrls[$question->value] = $this->messageUrlBuilder->getMessageJumpUrl($message);

        $this->unprocessedTasks -= 1;
        if ($this->unprocessedTasks > 0) {
            return;
        }

        $description = "";
        foreach($this->messageUrls as $name => $url) {
            $description .= "[$name]($url)".PHP_EOL;
        }

        $embed = new Embed($this->discord, ['description' => $description, 'title' => 'Содержание']);
        $this->channel->sendEmbed($embed);
    }
}
