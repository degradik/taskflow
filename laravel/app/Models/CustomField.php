<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomField extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'title',
        'type',
        'options'
    ];

    protected $casts = [
        'options' => 'array',
    ];

    // Связь с значениями этого поля
    public function values(): HasMany
    {
        return $this->hasMany(CustomFieldValue::class, 'custom_field_id');
    }

    // Связь с задачей
    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }
}
