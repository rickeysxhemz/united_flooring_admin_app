<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Spatie\Permission\Models\Role;
use App\Models\User;

class UserRoleValidation implements Rule
{
    protected $roleToCheck;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($roleToCheck)
    {
        $this->roleToCheck = $roleToCheck;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $user=User::find($value);
        return $user && $user->hasRole($this->roleToCheck);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "The selected receiver is not a user with the '{$this->roleToCheck}' role";
    }
}
