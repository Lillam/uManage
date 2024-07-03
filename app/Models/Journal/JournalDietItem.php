<?php

namespace App\Models\Journal;

use Illuminate\Database\Eloquent\Model;

class JournalDietItem extends Model
{
    protected $table = 'journal_diet_item';

    protected $fillable = [
        'name',
        'category',
        'calories',
        'image_url'
    ];

    protected $casts = [
        'name'     => 'string',
        'category' => 'string',
        'calories' => 'int',
        'image_url' => 'string'
    ];
}
