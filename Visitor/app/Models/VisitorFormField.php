<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorFormField extends Model
{
    use HasFactory;

    public const TYPES = ['text', 'textarea', 'number', 'date'];

    protected $fillable = [
        'label',
        'field_type',
        'placeholder',
        'helper_text',
        'is_required',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_required' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public static function orderedActive()
    {
        return static::active()
            ->orderBy('sort_order')
            ->orderBy('label')
            ->get();
    }

    public static function validationRules(string $prefix = 'custom_fields'): array
    {
        return static::orderedActive()
            ->mapWithKeys(function (self $field) use ($prefix) {
                $rules = [$field->is_required ? 'required' : 'nullable'];

                $rules[] = match ($field->field_type) {
                    'number' => 'numeric',
                    'date' => 'date',
                    default => 'string',
                };

                if (in_array($field->field_type, ['text', 'textarea'], true)) {
                    $rules[] = 'max:2000';
                }

                return ["{$prefix}.{$field->id}" => $rules];
            })
            ->all();
    }

    public static function formatResponses(array $values): array
    {
        return static::orderedActive()
            ->mapWithKeys(function (self $field) use ($values) {
                $value = $values[$field->id] ?? null;

                if (is_string($value)) {
                    $value = trim($value);
                }

                if ($value === null || $value === '') {
                    return [];
                }

                return [
                    $field->id => [
                        'label' => $field->label,
                        'type' => $field->field_type,
                        'value' => $value,
                    ],
                ];
            })
            ->all();
    }
}
