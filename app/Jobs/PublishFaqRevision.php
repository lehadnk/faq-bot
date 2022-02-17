<?php

namespace App\Jobs;

use App\Models\Revision;
use App\Services\Discord\DiscordFacade;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
    public function handle(DiscordFacade $discordFacade)
    {
        $discordFacade->postRevision($this->revision);
    }
}
