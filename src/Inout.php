<?php

namespace Mchervenkov\Inout;

use Mchervenkov\Inout\Actions\ManageAdditionalServices;
use Mchervenkov\Inout\Actions\ManageAWB;
use Mchervenkov\Inout\Actions\ManageCompanies;
use Mchervenkov\Inout\Actions\ManageCouriers;
use Mchervenkov\Inout\Actions\ManageLocations;

class Inout
{
    use MakesHttpRequests;
    use ManageCouriers;
    use ManageCompanies;
    use ManageAWB;
    use ManageAdditionalServices;
    use ManageLocations;

    public const SIGNATURE = 'CARRIER_INOUT';

    /**
     * Inout Bearer Token
     */
    private string $apiToken;

    /**
     * Inout Api Base Url
     */
    private string $baseUrl;

    /**
     * Inout Api Timeout
     */
    private int $timeout;

    /**
     * Inout Api Timeout
     */
    private int $testMode;

    /**
     * Inout Company ID
     */
    private int $companyId;


    public function __construct()
    {
        $this->apiToken = config('inout.api_token');
        $this->testMode = config('inout.test_mode');
        $this->companyId = config('inout.company_id');
        $this->configBaseUrl();
    }

    /**
     * configBaseUrl
     *
     * @return void
     */
    public function configBaseUrl()
    {
        if (config('inout.env') == 'production') {
            $this->baseUrl = config('inout.production_base_url');
        } else {
            $this->baseUrl = config('inout.test_base_url');
        }
    }

    /**
     * Set Api token
     *
     * @param string $apiToken
     * @return void
     */
    public function setApiToken(string $apiToken)
    {
        $this->apiToken = $apiToken;
    }

    /**
     * Get Api Token
     *
     * @return string
     */
    public function getApiToken(): string
    {
        return $this->apiToken;
    }

    /**
     * setBaseUrl
     *
     * @param  string $baseUrl
     * @return void
     */
    public function setBaseUrl(string $baseUrl)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    /**
     * getBaseUrl
     *
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * setTimeout
     *
     * @param  int $timeout
     * @return void
     */
    public function setTimeout(int $timeout)
    {
        $this->timeout = $timeout;
    }

    /**
     * getTimeout
     *
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * setTestMode
     *
     * @param  int $testMode
     * @return void
     */
    public function setTestMode(int $testMode)
    {
        $this->testMode = $testMode;
    }

    /**
     * getCompanyId
     *
     * @return int
     */
    public function getCompanyId(): int
    {
        return $this->companyId;
    }

    /**
     * setCompanyId
     *
     * @param  int $companyId
     * @return void
     */
    public function setCompanyId(int $companyId)
    {
        $this->companyId = $companyId;
    }

    /**
     * getTestMode
     *
     * @return int
     */
    public function TestMode(): int
    {
        return $this->testMode;
    }

    /**
     * getSignature
     *
     * @return string
     */
    public function getSignature(): string
    {
        return self::SIGNATURE;
    }
}
