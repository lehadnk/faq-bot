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
use React\Promise\ExtendedPromiseInterface;

class QuestionPublisherFacade
{
    private EmojiFacade $emojiFacade;
    private $questionGlossaryAnchorWasSent = false;

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
        $this->questionGlossaryAnchorWasSent = false;

        if ($question->display_title) {
            $promise = $channel->sendMessage("**$question->value**");
            $this->addMessageUrlToGlossaryPromise($promise, $question, $glossaryPostProcessor);
        }

        foreach ($question->messages()->orderBy('order')->get() as $message)
        {
            /** @var \App\Models\Message $message */
            if ($message->content) {
                $contents = $this->emojiFacade->replaceEmojis($message->content, $emojiStorageDto);

                if ($message->render_as_embed) {
                    $promise = $channel->sendEmbed(
                        new Embed($discord, ['description' => $contents])
                    );
                    $this->addMessageUrlToGlossaryPromise($promise, $question, $glossaryPostProcessor);
                } else {
                    $promise = $channel->sendMessage($contents);
                    $this->addMessageUrlToGlossaryPromise($promise, $question, $glossaryPostProcessor);
                }
            }
            if ($message->image) {
                $contents = Storage::disk('s3')->get($message->image);
                file_put_contents("/tmp/{$message->image}", $contents);
                $promise = $channel->sendFile("/tmp/{$message->image}");
                $this->addMessageUrlToGlossaryPromise($promise, $question, $glossaryPostProcessor);
            }
        }
        $channel->sendMessage("__".PHP_EOL."__");
    }

    private function addMessageUrlToGlossaryPromise(ExtendedPromiseInterface $promise, Question $question, GlossaryPostProcessor $glossaryPostProcessor)
    {
        if ($this->questionGlossaryAnchorWasSent) {
            return;
        }

        $promise->then(function(Message $msg) use ($question, $glossaryPostProcessor) {
            $glossaryPostProcessor->onMessageProcessed($msg, $question);
        });

        $this->questionGlossaryAnchorWasSent = true;
    }
}
