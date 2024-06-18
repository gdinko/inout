<?php

namespace Mchervenkov\Inout\Actions;

use Mchervenkov\Inout\Exceptions\InoutException;
use Mchervenkov\Inout\Exceptions\InoutValidationException;
use Mchervenkov\Inout\Hydrators\City;

trait ManageLocations
{
    /**
     * We can now offer you the ability to ask for cities suggestions directly from your company’s software or website
     * by taking advantage of "Cities Suggestions Web Service".
     *
     * GET / Cities_Suggestions_Web_Service_v1.0
     *
     * @param int $countryId
     * @param string $searchString
     * @param int $searchAllFields
     * @return mixed
     * @throws InoutException
     */
    public function getCitiesSuggestions(int $countryId, string $searchString, int $searchAllFields = 1): mixed
    {
        return $this->get("get-cities/suggestions/$countryId/$searchString", [
            'testMode' => $this->testMode,
            'searchAllFields' => $searchAllFields
        ]);
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
        return $this->get("get-cities/$countryId", $city->validated());
    }

    /**
     * Most of the countries doesn't have county. For more specific data use actions is country prefix.
     * Ex. getRomaniaCounties()
     *
     * Get Counties
     *
     * @param int $countryId
     * @return mixed
     * @throws InoutException
     */
    public function getCounties(int $countryId)
    {
        return $this->get("get-counties/$countryId");
    }


}
