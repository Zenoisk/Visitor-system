<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VisitorFormField;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class VisitorFormFieldController extends Controller
{
    public function index(): View
    {
        return view('admin.form-fields.index', [
            'fields' => VisitorFormField::orderBy('sort_order')->orderBy('label')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.form-fields.create', [
            'field' => new VisitorFormField(['field_type' => 'text', 'is_active' => true]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        VisitorFormField::create($this->validatedData($request));

        return redirect()->route('admin.form-fields.index')->with('success', 'Form field added.');
    }

    public function edit(VisitorFormField $formField): View
    {
        return view('admin.form-fields.edit', [
            'field' => $formField,
        ]);
    }

    public function update(Request $request, VisitorFormField $formField): RedirectResponse
    {
        $formField->update($this->validatedData($request));

        return redirect()->route('admin.form-fields.index')->with('success', 'Form field updated.');
    }

    public function destroy(VisitorFormField $formField): RedirectResponse
    {
        $formField->delete();

        return redirect()->route('admin.form-fields.index')->with('success', 'Form field deleted.');
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:120'],
            'field_type' => ['required', Rule::in(VisitorFormField::TYPES)],
            'placeholder' => ['nullable', 'string', 'max:160'],
            'helper_text' => ['nullable', 'string', 'max:180'],
            'is_required' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
        ]);

        $data['is_required'] = $request->boolean('is_required');
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }
}
