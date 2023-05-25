<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCourseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'course_num' => 'required|string|max:6',
            'desc' => 'required|string',
            'field_id' => 'required|exists:fields,id',
            'advisor_id' => 'required|exists:advisors,id',
            'duration' => 'required|integer',
            'duration_unit' => 'required|in:days,weeks,months',
            'location' => 'nullable|string|max:255',
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'fees' => 'nullable|numeric',
            'capacity' => 'nullable|integer',
        ];
    }
}
