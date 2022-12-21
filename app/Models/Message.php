<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\SortableTrait;

/**
 * @property int $id
 * @property int $question_id
 * @property string $content
 * @property string $image
 * @property boolean $render_as_embed
 * @property int $order
 */
class Message extends Model
{
    use HasFactory;
    use SortableTrait;

    public $sortable = [
        'order_column_name' => 'order',
        'sort_when_creating' => true,
        'sort_on_has_many' => true,
    ];

    public function parent()
    {
        return $this->belongsTo(Question::class, "question_id");
    }
}
