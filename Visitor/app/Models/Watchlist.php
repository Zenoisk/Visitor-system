<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Watchlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'ic_passport_no',
        'reason',
        'created_by',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Check if an IC/Passport number is on the watchlist.
     */
    public static function isBlacklisted(string $icPassportNo): bool
    {
        return static::where('ic_passport_no', strtoupper(trim($icPassportNo)))->exists();
    }
}
