<?php

namespace App\Models;

use ChrisWare\NovaBreadcrumbs\Traits\Breadcrumbs;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\EloquentSortable\SortableTrait;

/**
 * @property int $id
 * @property int $revision_id
 * @property string $value
 * @property int $order
 *
 * @property Message[] $messages
 */
class Question extends Model
{
    use HasFactory;
    use SortableTrait;
    use Breadcrumbs;

    public $sortable = [
        'order_column_name' => 'order',
        'sort_when_creating' => true,
        'sort_on_has_many' => true,
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
