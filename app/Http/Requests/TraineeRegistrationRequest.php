<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TraineeRegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:25',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|string|min:8',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'degree' => 'required|in:bachelor,master,phd',
            'files' => 'required|array',
            'files.*' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ];
    }
}
