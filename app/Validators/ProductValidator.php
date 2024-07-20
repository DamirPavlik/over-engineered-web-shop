<?php

namespace App\Validators;

use App\Contracts\ValidatorInterface;
use App\Exception\ValidationException;
use Valitron\Validator;

class ProductValidator implements ValidatorInterface
{
    public function validate(array $data): array
    {
        $validator = new Validator($data);

        $validator->rule('required', ['title', 'description', 'price', 'stockQuantity']);
        $validator->rule('min', 'price', 0);
        $validator->rule('min', 'stockQuantity', 0);

        if (!$validator->validate()) {
            throw new ValidationException("Skill issues adding a product: " . $validator->errors());
        }

        return $data;
    }
}