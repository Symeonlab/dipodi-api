<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateUserProfileRequest extends FormRequest
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
            // User account fields (optional updates)
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255'],

            // Sport/Activity Profile
            'discipline' => ['nullable', 'string', Rule::in(['FOOTBALL', 'FUTSAL', 'FITNESS', 'PADEL'])],
            'position' => ['nullable', 'string', 'max:100'],
            'in_club' => ['nullable', 'boolean'],
            'match_day' => ['nullable', 'string', Rule::in(['AUCUN', 'LUNDI', 'MARDI', 'MERCREDI', 'JEUDI', 'VENDREDI', 'SAMEDI', 'DIMANCHE'])],
            'training_days' => ['nullable', 'array'],
            'training_days.*' => ['string', Rule::in([
                // Full French names
                'LUNDI', 'MARDI', 'MERCREDI', 'JEUDI', 'VENDREDI', 'SAMEDI', 'DIMANCHE',
                // Short French names
                'LUN', 'MAR', 'MER', 'JEU', 'VEN', 'SAM', 'DIM',
            ])],
            'training_focus' => ['nullable', 'string', 'max:100'],
            'level' => ['nullable', 'string', Rule::in([
                'DEBUTANT', 'INTERMEDIAIRE', 'AVANCE', 'PROFESSIONNEL',
                'DÉBUTANT', 'INTERMÉDIAIRE', 'AVANCÉ',
            ])],
            'training_location' => ['nullable', 'string', Rule::in([
                // Legacy values
                'SI MAISON', 'SI DEHORS', 'SI CARDIO EN SALLE',
                // From onboarding-data API
                'MAISON', 'DEHORS', 'CARDIO_EN_SALLE', 'MUSCULATION_EN_SALLE', 'MUSCULATION_ET_CARDIO_EN_SALLE',
                // English values used by some iOS views
                'HOME', 'GYM', 'OUTDOOR', 'MIXED',
            ])],
            'gym_preferences' => ['nullable', 'array'],
            'gym_preferences.*' => ['string'],
            'cardio_preferences' => ['nullable', 'array'],
            'cardio_preferences.*' => ['string'],
            'outdoor_preferences' => ['nullable', 'array'],
            'outdoor_preferences.*' => ['string'],
            'home_preferences' => ['nullable', 'array'],
            'home_preferences.*' => ['string'],

            // Personal Information
            'age' => ['nullable', 'integer', 'min:16', 'max:100'],
            'weight' => ['nullable', 'numeric', 'min:30', 'max:300'],
            'height' => ['nullable', 'numeric', 'min:100', 'max:250'],
            'ideal_weight' => ['nullable', 'numeric', 'min:30', 'max:300'],
            'gender' => ['nullable', 'string', Rule::in(['HOMME', 'FEMME'])],
            'birth_date' => ['nullable', 'date'],
            'country' => ['nullable', 'string', 'max:100'],
            'region' => ['nullable', 'string', 'max:100'],
            'pro_level' => ['nullable', 'string', 'max:100'],
            'goal' => ['nullable', 'string', Rule::in([
                // Legacy values
                'PERTE_DE_POIDS', 'PRISE_DE_MASSE', 'MAINTIEN', 'PERFORMANCE', 'SECHE',
                // From onboarding-data API
                'PERDRE_DU_POIDS', 'MASSE_MUSCULAIRE', 'MAINTIEN_DE_FORME',
            ])],
            'morphology' => ['nullable', 'string', Rule::in(['ECTOMORPHE', 'MESOMORPHE', 'ENDOMORPHE'])],
            'activity_level' => ['nullable', 'string', 'max:100'],

            // Nutrition Preferences
            'is_vegetarian' => ['nullable', 'boolean'],
            'meals_per_day' => ['nullable', 'string', 'max:50'],
            'breakfast_preferences' => ['nullable', 'array'],
            'breakfast_preferences.*' => ['string'],
            'bad_habits' => ['nullable', 'array'],
            'bad_habits.*' => ['string'],
            'snacking_habits' => ['nullable', 'string', 'max:100'],
            'vegetable_consumption' => ['nullable', 'string', 'max:50'],
            'fish_consumption' => ['nullable', 'string', 'max:50'],
            'meat_consumption' => ['nullable', 'string', 'max:50'],
            'dairy_consumption' => ['nullable', 'string', 'max:50'],
            'sugary_food_consumption' => ['nullable', 'string', 'max:50'],
            'cereal_consumption' => ['nullable', 'string', 'max:50'],
            'starchy_food_consumption' => ['nullable', 'string', 'max:50'],
            'sugary_drink_consumption' => ['nullable', 'string', 'max:50'],
            'egg_consumption' => ['nullable', 'string', 'max:50'],
            'fruit_consumption' => ['nullable', 'string', 'max:50'],

            // Medical Information
            'has_injury' => ['nullable', 'boolean'],
            'injury_location' => ['nullable', 'string', 'max:100'],
            'has_diabetes' => ['nullable', 'boolean'],
            'takes_medication' => ['nullable', 'boolean'],
            'hormonal_issues' => ['nullable', 'string', Rule::in([
                'OUI', 'NON', 'JE NE SAIS PAS',
                // From onboarding-data API
                'TROUBLES_HORMONAUX_OUI', 'TROUBLES_HORMONAUX_NON', 'TROUBLES_HORMONAUX_NSP',
            ])],
            'family_history' => ['nullable', 'array'],
            'family_history.*' => ['string'],
            'medical_history' => ['nullable', 'array'],
            'medical_history.*' => ['string'],

            // Onboarding Status
            'is_onboarding_complete' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'age.min' => 'L\'âge minimum est de 16 ans.',
            'age.max' => 'L\'âge maximum est de 100 ans.',
            'weight.min' => 'Le poids minimum est de 30 kg.',
            'weight.max' => 'Le poids maximum est de 300 kg.',
            'height.min' => 'La taille minimum est de 100 cm.',
            'height.max' => 'La taille maximum est de 250 cm.',
            'discipline.in' => 'La discipline doit être FOOTBALL, FUTSAL, FITNESS ou PADEL.',
            'gender.in' => 'Le genre doit être HOMME ou FEMME.',
            'goal.in' => 'L\'objectif n\'est pas valide.',
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
