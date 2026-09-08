<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    // defalt value alignment
    protected $attributes = [
        'is_completed'=> false,
    ];

    // protect mass assignment
    protected $fillable = [
        'title',
        'description',
        'is_completed',
    ];

    // convert type boolean
    protected function casts(): array
    {
        return [
            'is_completed' => 'boolean',
        ];
    }
}
