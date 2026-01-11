<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMaintenanceRequest extends FormRequest
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
            'vehicle_id' => 'required|exists:vehicles,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:scheduled,unscheduled,inspection,repair',
            'status' => 'required|in:scheduled,in_progress,completed,cancelled',
            'cost' => 'nullable|numeric|min:0|max:999999.99',
            'provider' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'vehicle_id.required' => 'Seleziona un veicolo.',
            'start_date.required' => 'La data di inizio è obbligatoria.',
            'end_date.after_or_equal' => 'La data di fine deve essere uguale o successiva alla data di inizio.',
            'reason.required' => 'Il motivo della manutenzione è obbligatorio.',
            'type.required' => 'Il tipo di manutenzione è obbligatorio.',
            'status.required' => 'Lo stato è obbligatorio.',
        ];
    }
}
