<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

/**
 * @property int $id
 * @property int $revision_id
 * @property string $value
 * @property int $order
 * @property bool $display_title
 *
 * @property Message[] $messages
 */
class Question extends Model implements Sortable
{
    use HasFactory;
    use SortableTrait;

    public $sortable = [
        'order_column_name' => 'order',
        'sort_when_creating' => true,
        'sort_on_has_many' => true,
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function parent()
    {
        return $this->belongsTo(Revision::class, "revision_id");
    }
}
