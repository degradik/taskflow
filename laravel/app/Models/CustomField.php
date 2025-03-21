<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomField extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'type',
        'entity_type',
        'options'
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public function values()
    {
        return $this->hasMany(CustomFieldValue::class);
    }
}
