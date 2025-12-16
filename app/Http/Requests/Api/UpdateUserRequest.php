<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\User;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Authorization is handled by UserPolicy in the controller
        return true;
    }

    public function rules(): array
    {
        /** @var User $user */
        $user = $this->route('user');

        return [
            'name' => ['sometimes', 'string', 'max:255'],

            'email' => [
                'sometimes',
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
            ],

            'password' => ['sometimes', 'min:8'],

            // Role is optional and will be stripped for non-admins in controller
            'role' => [
                'sometimes',
                Rule::in([
                    User::ROLE_ADMIN,
                    User::ROLE_TEAM_LEADER,
                    User::ROLE_TEAM_MEMBER,
                ]),
            ],
        ];
    }
}
