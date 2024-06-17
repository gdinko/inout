<?php

namespace Mchervenkov\Inout\Actions;

use Illuminate\Validation\ValidationException;
use Mchervenkov\Inout\Exceptions\InoutException;
use Mchervenkov\Inout\Exceptions\InoutValidationException;
use Mchervenkov\Inout\Hydrators\City;

trait ManageCouriers
{
    /**
     * We can now offer you the ability to ask for couriers directly from your company’s software or website by
     * taking advantage of "Couriers Web Service".
     *
     * GET / Couries_Web_Service_v1.0
     *
     * @return mixed
     * @throws InoutException
     */
    public function getCouriers(): mixed
    {
        return $this->get("couriers/$this->companyId");
    }

    /**
     * We can now offer you the ability to ask for cities directly from your company’s software or
     * website by taking advantage of "Cities Web Service".
     *
     * GET / Cities_Web_Service_v1.0
     *
     * @param City $city
     * @param int $countryId
     * @return mixed
     * @throws InoutException
     * @throws InoutValidationException
     */
    public function getCities(City $city, int $countryId): mixed
    {
        return $this->get("get-cities/$countryId?" . http_build_query($city->validated()));
    }
}
