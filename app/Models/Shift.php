<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shift extends Model
{
    /** @use HasFactory<\Database\Factories\ShiftFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'time_in',
        'time_out',
        'grace_period_minutes',
        'break_duration_minutes',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'time_in'                => 'datetime:H:i',
            'time_out'               => 'datetime:H:i',
            'grace_period_minutes'   => 'integer',
            'break_duration_minutes' => 'integer',
            'is_active'              => 'boolean',
        ];
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
