<?php

namespace Speelpenning\PostcodeNl\Http\Controllers;

use Illuminate\Validation\ValidationException;
use Illuminate\Routing\Controller;
use Speelpenning\PostcodeNl\Exceptions\AddressNotFound;
use Speelpenning\PostcodeNl\Exceptions\Unauthorized;
use Speelpenning\PostcodeNl\Services\AddressLookup;

class AddressController extends Controller
{
    /**
     * @var AddressLookup
     */
    protected $lookup;

    /**
     * AddressController constructor.
     *
     * @param AddressLookup $lookup
     */
    public function __construct(AddressLookup $lookup)
    {
        $this->lookup = $lookup;
    }

    /**
     * Performs a Dutch address lookup and returns a JSON response.
     *
     * @param string $postcode
     * @param int $houseNumber
     * @param null|string $houseNumberAddition
     * @return \Illuminate\Http\JsonResponse
     */
    public function get($postcode, $houseNumber, $houseNumberAddition = null)
    {
        try {
            $address = $this->lookup->lookup(str_replace(' ', '', $postcode), $houseNumber, $houseNumberAddition);
            return response()->json($address);
        } catch (ValidationException $e) {
            abort(400, 'Bad Request');
        } catch (Unauthorized $e) {
            abort(401, 'Unauthorized');
        } catch (AddressNotFound $e) {
            abort(404, 'Not Found');
        }
    }
}
