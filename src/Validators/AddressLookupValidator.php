<?php

namespace Speelpenning\PostcodeNl\Validators;

use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Validation\Factory;

class AddressLookupValidator
{
    /**
     * @var Factory
     */
    protected $validator;

    protected $rules = [
        'postcode' => ['required', 'regex:/^[1-9]{1}[0-9]{3}[A-Z]{2}$/'],
        'houseNumber' => ['required', 'integer', 'between:0,99999'],
        'houseNumberAddition' => ['string']
    ];

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
    public function validate(array $data = [])
    {
        $validation = $this->validator->make($data, $this->rules);

        if ($validation->fails()) {
            throw new ValidationException($validation->errors());
        }
    }
}
