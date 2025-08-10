<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SpamKeyword extends Model
{
    use HasFactory;

    protected $fillable = [
        'keyword',
        'weight',
        'is_regex',
        'is_active',
        'category',
        'description'
    ];

    protected function casts(): array
    {
        return [
            'is_regex' => 'boolean',
            'is_active' => 'boolean',
            'weight' => 'integer',
        ];
    }

    /**
     * Scope for active keywords
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope by category
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }
}
