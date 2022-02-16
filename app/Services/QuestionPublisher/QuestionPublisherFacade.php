<?php

namespace App\Services\QuestionPublisher;

use App\Models\Question;
use App\Services\Emoji\Dto\EmojiStorageDto;
use App\Services\Emoji\EmojiFacade;
use Discord\Parts\Channel\Channel;
use Illuminate\Support\Facades\Storage;

class QuestionPublisherFacade
{
    private EmojiFacade $emojiFacade;

    public function __construct(EmojiFacade $emojiFacade)
    {
        $this->emojiFacade = $emojiFacade;
    }

    public function render(Channel $channel, Question $question, EmojiStorageDto $emojiStorageDto)
    {
        $channel->sendMessage("**$question->value**");
        foreach ($question->messages()->orderBy('order')->get() as $message)
        {
            if ($message->content) {
                $contents = $this->emojiFacade->replaceEmojis($message->content, $emojiStorageDto);
                $channel->sendMessage($contents);
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
