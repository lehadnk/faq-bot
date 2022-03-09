<?php

namespace App\Services\Discord;

use App\Models\Revision;
use App\Models\User;
use App\Services\Discord\API\DiscordApi;
use App\Services\DiscordChannel\DiscordChannelFacade;
use App\Services\Emoji\Dto\EmojiStorageDto;
use App\Services\Emoji\EmojiFacade;
use App\Services\RevisionPublisher\RevisionPublisherFacade;
use Carbon\Carbon;

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

    public function postRevision(Revision $revision, User $publisher)
    {
        $api = $this->getDiscordFactory()->getDiscordApi();
        $api->setOnReadyHandler(function(DiscordApi $discord) use ($revision, $publisher) {
            $this->emojiFacade->loadEmojiList($discord->getDiscord(), function(EmojiStorageDto $emojiStorageDto) use ($discord, $revision, $publisher) {
                $this->discordChannelFacade->emptyChannel($discord->getDiscord(), $revision->channel->discord_channel_id);
                $this->revisionPublisherFacade->render($discord->getDiscord(), $revision, $emojiStorageDto);
//
                $revision->channel->last_published_by = $publisher->id;
                $revision->channel->last_published_at = Carbon::now();
                $revision->channel->save();

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
