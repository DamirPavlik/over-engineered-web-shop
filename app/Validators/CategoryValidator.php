<?php

namespace App\Validators;

use App\Contracts\ValidatorInterface;
use App\Exception\ValidationException;
use Valitron\Validator;

class CategoryValidator implements ValidatorInterface
{
    public function validate(array $data): array
    {
        $validator = new Validator($data);

        $validator->rule('required', ['title']);

        if (!$validator->validate()) {
            throw new ValidationException("Skill issues adding a category: " . $validator->errors());
        }

        return $data;
    }
}