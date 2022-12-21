<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $channel_id
 * @property Channel $channel
 * @property Question[] $questions
 * @property int $last_published_by
 * @property Carbon $last_published_at
 */
class Revision extends Model
{
    use HasFactory;

    protected $dates = ['last_published_at'];

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }

    public function parent()
    {
        return $this->belongsTo(Channel::class, "channel_id");
    }
}
