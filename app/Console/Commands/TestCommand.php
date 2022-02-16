<?php

namespace App\Console\Commands;

use App\Models\Revision;
use App\Services\Discord\API\DiscordApi;
use App\Services\Discord\DiscordFacade;
use App\Services\Emoji\EmojiFacade;
use App\Services\RevisionPublisher\RevisionPublisherFacade;
use Discord\Discord;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:bot';

    private const MAX_CONNECTION_ATTEMPTS = 10;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tests bot';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle(DiscordFacade $discordFacade, RevisionPublisherFacade $revisionPublisherFacade, EmojiFacade $emojiFacade)
    {
        $revision = Revision::where('id', 8)->first();
        Log::debug('Starting job handle');
        $discordFacade->postRevision($revision);
    }
}
