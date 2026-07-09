<?php

namespace App\Http\Requests;

use App\Models\Visitor;
use App\Models\VisitorFormField;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VisitorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'ic_passport_no' => ['required', 'string', 'max:50', 'regex:/^[A-Za-z0-9\-\/]+$/'],
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9\+\-\s\(\)]+$/'],
            'email' => ['nullable', 'email', 'max:255'],
            'company_name' => ['required', 'string', 'max:255'],
            'purpose_of_visit' => ['required', 'string', 'max:2000'],
            'person_to_meet' => ['required', 'string', 'max:255'],
            'department' => ['required', 'string', 'max:255'],
            'vehicle_plate_no' => ['nullable', 'string', 'max:30'],
            'visit_date' => ['required', 'date'],
            'status' => ['required', Rule::in(Visitor::STATUSES)],
            'remarks' => ['nullable', 'string', 'max:2000'],
            'custom_fields' => ['nullable', 'array'],
        ] + VisitorFormField::validationRules();
    }

    public function validated($key = null, $default = null)
    {
        $data = parent::validated($key, $default);

        if ($key !== null) {
            return $data;
        }

        $data['custom_fields'] = VisitorFormField::formatResponses($this->input('custom_fields', []));

        return $data;
    }
}
