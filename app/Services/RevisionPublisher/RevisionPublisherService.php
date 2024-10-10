<?php

namespace App\Services\RevisionPublisher;

use App\Models\Revision;
use App\Services\Emoji\Dto\EmojiStorageDto;
use App\Services\Emoji\EmojiService;
use App\Services\Glossary\Business\GlossaryPostProcessor;
use App\Services\Glossary\Business\MessageUrlBuilder;
use App\Services\QuestionPublisher\QuestionPublisherService;
use Discord\Discord;

class RevisionPublisherService
{
    private QuestionPublisherService $questionPublisherFacade;

    public function __construct(
        QuestionPublisherService $questionPublisherFacade,
    ) {
        $this->questionPublisherFacade = $questionPublisherFacade;
    }

    public function render(Discord $discord, Revision $revision, EmojiStorageDto $emojiStorageDto)
    {
        $channel = $discord->getChannel($revision->channel->discord_channel_id);

        $questions = $revision->questions()->orderBy('order')->get();

        $postProcessor = new GlossaryPostProcessor($discord, $channel, new MessageUrlBuilder(), $questions->count());

        foreach($questions as $question) {
            $this->questionPublisherFacade->render($discord, $channel, $question, $emojiStorageDto, $postProcessor);
        }
    }
}
