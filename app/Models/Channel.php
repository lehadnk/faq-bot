<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $discord_server_id
 * @property string $discord_server_name
 * @property string $discord_channel_id
 * @property string $discord_channel_name
 */
class Channel extends Model
{
    use HasFactory;

    public function revisions(): HasMany
    {
        return $this->hasMany(Revision::class);
    }
}
