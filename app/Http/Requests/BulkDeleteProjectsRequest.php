<?php

namespace App\Http\Requests;

use App\Models\Project;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BulkDeleteProjectsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('bulkDeleteAny', Project::class) ?? false;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'project_ids' => ['required', 'array', 'min:1', 'max:500'],
            'project_ids.*' => [
                'required',
                'integer',
                'distinct',
                Rule::exists('projects', 'id')->whereNull('deleted_at'),
            ],
        ];
    }
}
