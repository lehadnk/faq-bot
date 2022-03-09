<?php

namespace App\Jobs;

use App\Models\Revision;
use App\Models\User;
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
    private User $publisher;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Revision $revision, User $publisher)
    {
        $this->revision = $revision;
        $this->publisher = $publisher;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(DiscordFacade $discordFacade)
    {
        $discordFacade->postRevision($this->revision, $this->publisher);
    }
}
