<?php

namespace App\Console\Commands;

use App\Models\Revision;
use App\Services\Discord\DiscordFacade;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish-revision {revision}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publishes revision';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle(DiscordFacade $discordFacade)
    {
        $revision = $this->input->getArgument('revision');
        $revision = Revision::where('id', $revision)->first();
        $discordFacade->postRevision($revision);
    }
}
