<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateReminderSettingsRequest extends FormRequest
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
            'workout_reminder_enabled' => ['nullable', 'boolean'],
            'workout_reminder_time' => ['nullable', 'date_format:H:i'],
            'meal_reminder_enabled' => ['nullable', 'boolean'],
            'meal_reminder_times' => ['nullable', 'array'],
            'meal_reminder_times.*' => ['date_format:H:i'],
            'water_reminder_enabled' => ['nullable', 'boolean'],
            'water_reminder_interval' => ['nullable', 'integer', 'min:30', 'max:480'],
            'progress_reminder_enabled' => ['nullable', 'boolean'],
            'progress_reminder_day' => ['nullable', 'string', 'in:LUNDI,MARDI,MERCREDI,JEUDI,VENDREDI,SAMEDI,DIMANCHE'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'workout_reminder_time.date_format' => 'L\'heure du rappel d\'entraînement doit être au format HH:MM.',
            'meal_reminder_times.*.date_format' => 'Les heures de rappel de repas doivent être au format HH:MM.',
            'water_reminder_interval.min' => 'L\'intervalle de rappel d\'eau doit être d\'au moins 30 minutes.',
            'water_reminder_interval.max' => 'L\'intervalle de rappel d\'eau ne peut pas dépasser 480 minutes (8 heures).',
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
