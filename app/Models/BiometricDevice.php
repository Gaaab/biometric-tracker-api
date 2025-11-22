<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BiometricDevice extends Model
{
    /** @use HasFactory<\Database\Factories\BiometricDeviceFactory> */
    use HasFactory;

    protected $fillable = [
        'device_id',
        'name',
        'location',
        'api_key',
        'status',
        'last_ping_at',
    ];

    protected function casts(): array
    {
        return [
            'device_id'    => 'string',
            'api_key'      => 'string',
            'status'       => 'string',
            'last_ping_at' => 'datetime',
        ];
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}
