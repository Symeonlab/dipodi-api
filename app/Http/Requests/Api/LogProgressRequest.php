<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LogProgressRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'date' => ['required', 'date', 'before_or_equal:today'],
            'weight' => ['nullable', 'numeric', 'min:30', 'max:300'],
            'waist' => ['nullable', 'numeric', 'min:40', 'max:200'],
            'chest' => ['nullable', 'numeric', 'min:50', 'max:200'],
            'hips' => ['nullable', 'numeric', 'min:50', 'max:200'],
            'calories_consumed' => ['nullable', 'integer', 'min:0', 'max:10000'],
            'calories_burned' => ['nullable', 'integer', 'min:0', 'max:5000'],
            'workout_completed' => ['nullable', 'string', 'max:255'], // Stores workout name or "true"/"false"
            'mood' => ['nullable', 'string', 'max:100'], // e.g., "energized", "tired", "good"
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'date.required' => 'La date est requise.',
            'date.before_or_equal' => 'La date ne peut pas être dans le futur.',
            'weight.min' => 'Le poids minimum est de 30 kg.',
            'weight.max' => 'Le poids maximum est de 300 kg.',
            'waist.min' => 'Le tour de taille minimum est de 40 cm.',
            'waist.max' => 'Le tour de taille maximum est de 200 cm.',
            'calories_consumed.max' => 'Les calories consommées ne peuvent pas dépasser 10000.',
            'calories_burned.max' => 'Les calories brûlées ne peuvent pas dépasser 5000.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors(),
        ], 422));
    }
}
