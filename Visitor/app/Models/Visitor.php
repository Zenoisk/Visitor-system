<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;

    public const STATUSES = ['pending', 'approved', 'rejected', 'checked_in', 'checked_out'];

    protected $fillable = [
        'visitor_no',
        'full_name',
        'ic_passport_no',
        'phone',
        'email',
        'company_name',
        'purpose_of_visit',
        'person_to_meet',
        'department',
        'vehicle_plate_no',
        'visit_date',
        'status',
        'is_blacklisted',
        'remarks',
        'custom_fields',
        'checked_in_at',
        'checked_out_at',
    ];

    protected function casts(): array
    {
        return [
            'visit_date' => 'date',
            'custom_fields' => 'array',
            'checked_in_at' => 'datetime',
            'checked_out_at' => 'datetime',
            'is_blacklisted' => 'boolean',
        ];
    }
}
