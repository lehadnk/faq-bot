<?php

namespace App\Jobs;

use App\Models\Revision;
use App\Services\Discord\API\DiscordApi;
use App\Services\Discord\DiscordFacade;
use App\Services\RevisionPublisher\RevisionPublisherFacade;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PublishFaqRevision implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private const MAX_CONNECTION_ATTEMPTS = 10;

    private Revision $revision;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Revision $revision)
    {
        $this->revision = $revision;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(DiscordFacade $discordFacade, RevisionPublisherFacade $revisionPublisherFacade)
    {
        Log::debug('Starting job handle');
        $api = $discordFacade->getDiscordApi();
        Log::debug('Got discord API');
        if (!$this->connectApi($api)) {
            throw new \Exception("Unable to establish connectiont to discord server");
        }

        $api->emptyChannel($this->revision->channel_id);
        $revisionPublisherFacade->render($api->getDiscord(), $this->revision);
    }

    private function connectApi(DiscordApi $discordApi)
    {
        Log::debug('Initializing connection...');
        $attempts = 0;
        while ($attempts < self::MAX_CONNECTION_ATTEMPTS) {
            if ($discordApi->getIsReady()) {
                Log::debug('Bot has been connected');
                return true;
            }

            sleep(1);
            $attempts++;
        }

        return false;
    }
}
