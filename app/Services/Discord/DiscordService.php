<?php

namespace App\Services\Discord;

use App\Models\Revision;
use App\Models\User;
use App\Services\Discord\API\DiscordApi;
use App\Services\DiscordChannel\DiscordChannelService;
use App\Services\Emoji\Dto\EmojiStorageDto;
use App\Services\Emoji\EmojiService;
use App\Services\RevisionPublisher\RevisionPublisherService;
use Carbon\Carbon;

class DiscordService
{
    private DiscordChannelService $discordChannelFacade;
    private RevisionPublisherService $revisionPublisherFacade;
    private EmojiService $emojiFacade;

    public function __construct(
        DiscordChannelService    $discordChannelFacade,
        RevisionPublisherService $revisionPublisherFacade,
        EmojiService             $emojiFacade
    )
    {
        $this->discordChannelFacade = $discordChannelFacade;
        $this->revisionPublisherFacade = $revisionPublisherFacade;
        $this->emojiFacade = $emojiFacade;
    }

    public function postRevision(Revision $revision, User $publisher)
    {
        ini_set('memory_limit', '-1');
        $api = $this->getDiscordFactory()->getDiscordApi();
        $api->setOnReadyHandler(function(DiscordApi $discord) use ($revision, $publisher) {
            $this->emojiFacade->loadEmojiList($discord->getDiscord(), function(EmojiStorageDto $emojiStorageDto) use ($discord, $revision, $publisher) {
                $this->discordChannelFacade->emptyChannel($discord->getDiscord(), $revision->channel->discord_channel_id);
                sleep(5);

                $this->revisionPublisherFacade->render($discord->getDiscord(), $revision, $emojiStorageDto);

                $revision->channel->last_published_by = $publisher->id;
                $revision->channel->last_published_at = Carbon::now();
                $revision->channel->save();

                $revision->last_published_by = $publisher->id;
                $revision->last_published_at = Carbon::now();
                $revision->save();

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
