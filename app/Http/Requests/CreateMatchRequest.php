<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateMatchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'home_team_id' => 'required',
            'guest_team_id' => 'required',
            'home_team_score' => 'required|numeric|min:0',
            'guest_team_score' => 'required|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'home_team_id.required' => 'Necessário selecionar o time da casa',
            'guest_team_id.required' => 'Necessário selecionar o time visitante',
            'home_team_score.required'        => 'Digite o placar do time da casa',
            'guest_team_score.required'        => 'Digite o placar do time visitante'
        ];
    }
}
