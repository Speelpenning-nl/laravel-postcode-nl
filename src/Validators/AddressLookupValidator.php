<?php

namespace Speelpenning\PostcodeNl\Validators;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Factory;
use Illuminate\Validation\ValidationException;

class AddressLookupValidator
{
    protected const RULES = [
        'postcode' => ['required', 'regex:/^[1-9]{1}[0-9]{3}[A-Za-z]{2}$/'],
        'houseNumber' => ['required', 'integer', 'between:0,99999'],
        'houseNumberAddition' => ['sometimes', 'string']
    ];

    /**
     * @var Factory
     */
    protected $validator;

    /**
     * AddressLookupValidator constructor.
     *
     * @param Factory $validator
     */
    public function __construct(Factory $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Validates the address lookup input.
     *
     * @param array $data
     * @throws ValidationException
     */
    public function validate(array $data = []): void
    {
        $validation = $this->validator->make($data, self::RULES);

        if ($validation->fails()) {
            throw new ValidationException($validation, new JsonResponse($validation->errors()));
        }
    }
}
