<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'string',
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public $resourceType = 'tasks';

    /*public function scopeTitle(Builder $query, $value)
    {
        $query->where('title', 'LIKE', '%'.$value.'%');
    }

    public function scopeDescription(Builder $query, $value)
    {
        $query->where('description', 'LIKE', '%'.$value.'%');
    }*/
}
