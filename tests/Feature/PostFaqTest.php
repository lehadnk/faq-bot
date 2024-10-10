<?php

namespace Tests\Feature;

use App\Models\Channel;
use App\Models\Message;
use App\Models\Question;
use App\Models\Revision;
use App\Models\User;
use App\Services\Discord\DiscordService;
use Illuminate\Support\Str;
use Tests\TestCase;

class PostFaqTest extends TestCase
{
    public function test_example()
    {
        $publisher = new User();
        $publisher->name = 'Alexey Zauzin';
        $publisher->email = Str::random(16) . '@' . Str::random(16) . '.com';
        $publisher->save();

        $channel = new Channel();
        $channel->discord_server_id = '927638318060015676';
        $channel->discord_server_name = 'lehadnk\'s server';
        $channel->discord_channel_id = '1095532179070648441';
        $channel->discord_channel_name = "guide";
        $channel->save();

        $revision = new Revision();
        $revision->channel_id = $channel->id;
        $revision->save();

        $question = new Question();
        $question->value = 'Как сейчас работает уменьшение урона от “Грехи многих”?';
        $question->revision_id = $revision->id;
        $question->save();

        $message = new Message();
        $message->question_id = $question->id;
        $message->content = "blah-blah";
        $message->image = 'https://cdn.discordapp.com/attachments/1095532179070648441/1095533583675969586/nAvWUdUD3712DvBscoYxdYT0iPuAkEXrKOAOzdk8.jpg?ex=67088b28&is=670739a8&hm=7dd638cc25342d0c4c3df2d07b5a09babd04c8738284300308a31e4a50743d05&';
        $message->save();

        /** @var DiscordService $discordService */
        $discordService = app(DiscordService::class);
        $discordService->postRevision($revision, $publisher);
    }
}
