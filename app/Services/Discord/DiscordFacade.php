<?php

namespace App\Services\Discord;

use App\Models\Revision;
use App\Services\Discord\API\DiscordApi;
use App\Services\DiscordChannel\DiscordChannelFacade;
use App\Services\Emoji\Dto\EmojiStorageDto;
use App\Services\Emoji\EmojiFacade;
use App\Services\RevisionPublisher\RevisionPublisherFacade;

class DiscordFacade
{
    private DiscordChannelFacade $discordChannelFacade;
    private RevisionPublisherFacade $revisionPublisherFacade;
    private EmojiFacade $emojiFacade;

    public function __construct(
        DiscordChannelFacade $discordChannelFacade,
        RevisionPublisherFacade $revisionPublisherFacade,
        EmojiFacade $emojiFacade
    )
    {
        $this->discordChannelFacade = $discordChannelFacade;
        $this->revisionPublisherFacade = $revisionPublisherFacade;
        $this->emojiFacade = $emojiFacade;
    }

    public function postRevision(Revision $revision)
    {
        $api = $this->getDiscordFactory()->getDiscordApi();
        $api->setOnReadyHandler(function(DiscordApi $discord) use ($revision) {
            $this->emojiFacade->loadEmojiList($discord->getDiscord(), function(EmojiStorageDto $emojiStorageDto) use ($discord, $revision) {
                $this->discordChannelFacade->emptyChannel($discord->getDiscord(), $revision->channel->discord_channel_id);
                $this->revisionPublisherFacade->render($discord->getDiscord(), $revision, $emojiStorageDto);
                $discord->getDiscord()->close(false);
            });
        });

        $api->run();
    }

    private function getDiscordFactory(): DiscordFactory
    {
        return new DiscordFactory();
    }
}
