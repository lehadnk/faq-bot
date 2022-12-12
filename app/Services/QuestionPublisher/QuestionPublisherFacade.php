<?php

namespace App\Services\QuestionPublisher;

use App\Models\Question;
use App\Services\Emoji\Dto\EmojiStorageDto;
use App\Services\Emoji\EmojiFacade;
use App\Services\Glossary\Business\GlossaryPostProcessor;
use App\Services\Glossary\Business\MessageUrlBuilder;
use Discord\Discord;
use Discord\Parts\Channel\Channel;
use Discord\Parts\Channel\Message;
use Discord\Parts\Embed\Embed;
use Illuminate\Support\Facades\Storage;

class QuestionPublisherFacade
{
    private EmojiFacade $emojiFacade;

    public function __construct(EmojiFacade $emojiFacade, MessageUrlBuilder $messageUrlBuilder)
    {
        $this->emojiFacade = $emojiFacade;
    }

    public function render(
        Discord $discord,
        Channel $channel,
        Question $question,
        EmojiStorageDto $emojiStorageDto,
        GlossaryPostProcessor $glossaryPostProcessor
    ) {
        $questionHeaderMessagePromise = $channel->sendMessage("**$question->value**");
        $questionHeaderMessagePromise->then(function(Message $msg) use ($question, $glossaryPostProcessor) {
            $glossaryPostProcessor->onMessageProcessed($msg, $question);
        });

        foreach ($question->messages()->orderBy('order')->get() as $message)
        {
            /** @var \App\Models\Message $message */
            if ($message->content) {
                $contents = $this->emojiFacade->replaceEmojis($message->content, $emojiStorageDto);

                if ($message->render_as_embed) {
                    $channel->sendEmbed(
                        new Embed($discord, ['description' => $contents])
                    );
                } else {
                    $channel->sendMessage($contents);
                }
            }
            if ($message->image) {
                $contents = Storage::disk('s3')->get($message->image);
                file_put_contents("/tmp/{$message->image}", $contents);
                $channel->sendFile("/tmp/{$message->image}");
            }
        }
        $channel->sendMessage("__".PHP_EOL."__");
    }
}
