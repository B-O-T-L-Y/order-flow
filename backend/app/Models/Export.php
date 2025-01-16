<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Export newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Export newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Export query()
 * @property int $id
 * @property int $user_id
 * @property string $file_path
 * @property string $format
 * @property array<array-key, mixed>|null $filters
 * @property string $status
 * @property \Illuminate\Support\Carbon $created_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Export whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Export whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Export whereFilters($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Export whereFormat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Export whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Export whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Export whereUserId($value)
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Export whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Export extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'file_path',
        'format',
        'status',
    ];
}
