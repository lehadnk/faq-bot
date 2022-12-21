<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string $discord_server_id
 * @property string $discord_server_name
 * @property string $discord_channel_id
 * @property string $discord_channel_name
 * @property int $last_published_by
 * @property Carbon $last_published_at
 */
class Channel extends Model
{
    use HasFactory;

    protected $dates = ['last_published_at'];

    public function revisions(): HasMany
    {
        return $this->hasMany(Revision::class);
    }

    public function lastPublishedBy(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'last_published_by');
    }
}
