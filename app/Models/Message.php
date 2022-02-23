<?php

namespace App\Models;

use ChrisWare\NovaBreadcrumbs\Traits\Breadcrumbs;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\SortableTrait;

/**
 * @property int $id
 * @property int $question_id
 * @property string $content
 * @property string $image
 * @property int $order
 */
class Message extends Model
{
    use HasFactory;
    use SortableTrait;
    use Breadcrumbs;

    public $sortable = [
        'order_column_name' => 'order',
        'sort_when_creating' => true,
        'sort_on_has_many' => true,
    ];
}
