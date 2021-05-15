<?php


namespace App\Validator;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;

class ShopValidator
{
    public function validate($input): \Symfony\Component\Validator\ConstraintViolationListInterface
    {
        $validator = Validation::createValidator();

        $constraints = new Assert\Collection([
            'name' => [new Assert\Length(['min' => 2]), new Assert\NotBlank],
            'phoneNumber' => [new Assert\Length(['min' => 2, 'max' => 14]), new Assert\NotBlank],
            'street' => [new Assert\Length(['min' => 2]), new Assert\NotBlank],
            'postCode' => [new Assert\Length(['min' => 2, 'max' => 9]), new Assert\NotBlank],
            'city' => [new Assert\Length(['min' => 2]), new Assert\NotBlank],
            'longitude' => [new Assert\Length(['min' => 2]), new Assert\NotBlank],
            'latitude' => [new Assert\Length(['min' => 2]), new Assert\NotBlank],
        ]);

        return $validator->validate($input, $constraints);
    }
}