<?php

namespace App\Validators;

use App\Contracts\ValidatorFactoryInterface;
use App\Contracts\ValidatorInterface;
use http\Exception\RuntimeException;
use Psr\Container\ContainerInterface;

class ValidatorFactory implements ValidatorFactoryInterface
{
    public function __construct(private readonly ContainerInterface $container)
    {
    }

    public function make(string $class): ValidatorInterface
    {
        $validator = $this->container->get($class);

        if ($validator instanceof ValidatorInterface) {
            return $validator;
        }

        throw new RuntimeException("That ain't no validator: ".  $class);
    }
}