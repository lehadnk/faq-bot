<?php

namespace App\Services\Emoji;

use App\Services\Emoji\Dto\EmojiStorageDto;
use Discord\Discord;
use Discord\Parts\Guild\Emoji;
use Discord\Parts\Guild\Guild;

class EmojiFacade
{
    public function loadEmojiList(Discord $discord, callable $onFinish)
    {
        $guilds = $discord->guilds->getIterator()->getArrayCopy();
        $guildsCnt = count($guilds);
        $guildsParsed = 0;

        $responseDto = new EmojiStorageDto();
        foreach($guilds as $guild) {
            /** @var Guild $guild */
            $guild->emojis->freshen()->done(function($onFreshen) use ($guildsCnt, &$guildsParsed, $onFinish, $responseDto) {
                foreach($onFreshen->getIterator()->getArrayCopy() as $emoji) {
                    /** @var Emoji $emoji */
                    $responseDto->store($emoji->name, $emoji->id);
                }

                $guildsParsed++;
                if ($guildsParsed === $guildsCnt) {
                    $onFinish($responseDto);
                }
            });
        }
    }

    public function replaceEmojis(string $content, EmojiStorageDto $emojiStorageDto): string
    {
        preg_match_all('/:\S*:/', $content, $matches);
        $emojis = array_unique($matches[0]);
        foreach ($emojis as $match) {
            $emojiName = trim($match, ':');
            if ($emojiCode = $emojiStorageDto->getByName($emojiName)) {
                $content = str_replace($match, "<:$emojiName:$emojiCode>", $content);
            }
        }

        return $content;
    }
}
