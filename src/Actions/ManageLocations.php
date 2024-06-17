<?php

namespace Mchervenkov\Inout\Actions;

use Mchervenkov\Inout\Exceptions\InoutException;

trait ManageLocations
{

    public function getCitiesSuggestions(): mixed
    {
        return $this->get("get-cities/suggestions/{countryId}/{string}");
    }

    /**
     * We can now offer you the ability to ask for countries directly from your company’s software or website by
     * taking advantage of "Countries Web Service".
     *
     * GET / Countries_Web_Service_v1.0
     *
     * @return mixed
     * @throws InoutException
     */
    public function getCountries(): mixed
    {
        return $this->get("get-countries");
    }
}
