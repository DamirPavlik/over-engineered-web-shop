<?php

namespace App\Validators;

use App\Contracts\ValidatorInterface;
use App\Exception\ValidationException;
use Valitron\Validator;

class EditUserValidator implements ValidatorInterface
{
    public function validate(array $data): array
    {
        $validator = new Validator($data);

        $validator->rule('required', ['name', 'email']);
        $validator->rule('email', 'email');

        if (!$validator->validate()) {
            throw new ValidationException("Lmao skill issues can't even edit a user: " . $validator->errors());
        }

        return $data;
    }
}