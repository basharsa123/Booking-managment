<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class CreateEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "title" => "required|string",
            "date" => "required|date",
            "image" => "required|image|mimes:jpg,webp,svg",
            "capacity" => "required|numeric"
        ];
    }

    public function messages(): array
    {
        return [
            "title.required" => "Title is required",
            "date.required" => "Date is required ",
            "image.required" => "Image is required",
            "capacity.required" => "Capacity is required",
            "capacity.numeric" => "Capacity must be number",
            "image.image" => "Image must be an image of .jpg or .png image"
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json($validator->errors(), 422)
        );
    }

}
