<?php

namespace App\Http\Requests\Auth;

use App\Models\Role;
use App\Traits\FailValidationTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    use FailValidationTrait;

    /**
     * Indicates whether validation should stop after the first rule failure.
     *
     * @var bool
     */
    protected $stopOnFirstFailure = true;

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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'role_id' => ['required', 'exists:roles,id'],
        ];
    }

    /**
     * Handle a passed validation attempt.
     */
    protected function passedValidation(): void
    {
        $this->merge([
            'credit' => $this->getCredit($this->role_id),
        ]);
    }

    public function getCredit($roleId): ?int
    {

        switch ($roleId) {
            case Role::PREMIUM:
                $credit = 40;
                break;
            case Role::REGULAR:
                $credit = 20;
                break;
            default:
                $credit = null;
                break;
        }

        return $credit;
    }
}
