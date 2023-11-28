<?php

namespace App\Http\Requests\Events;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => ['required'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
            'summary' => ['required'],
            'description' => ['required'],
            'event_id' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
