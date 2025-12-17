<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeamRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool Always returns true, allowing all users to proceed with the update team request.
     *              Consider implementing proper authorization logic to restrict access to authorized users only.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * This method defines the validation rules for updating a team resource.
     * The rules are applied conditionally, allowing partial updates of team data.
     *
     * @return array An associative array of field names and their validation rules.
     *               - name: Optional field that must be a string with a maximum length of 255 characters.
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
        ];
    }
}
