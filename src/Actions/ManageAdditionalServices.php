<?php

namespace Mchervenkov\Inout\Actions;

use Mchervenkov\Inout\Exceptions\InoutException;

trait ManageAdditionalServices
{
    /**
     * We can now offer you the ability to ask for cod information directly from your company’s software or website by
     * taking advantage of "COD Information Web Service".
     *
     * With this service, you can receive detailed COD information. In "referenceNumber" property, you can fill the
     * value you filled in the "referenceNumber" when creating the AWB. The value can be an order number, a product number
     * or an invoice number.
     *
     * GET / COD_Information_Web_Service_v1.0
     *
     * @param string $referenceNumber
     * @return mixed
     * @throws InoutException
     */
    public function codInformation(string $referenceNumber): mixed
    {
        return $this->get("check-cod-by-order/$referenceNumber", [
            'testMode' => $this->testMode
        ]);
    }

    /**
     * We can now offer you the ability to ask for a phone call history directly from your company’s software or Website
     * by taking advantage of "Phone Call History Web Service".
     *
     * GET / Phone_Call_History_Web_Service_v1.0
     *
     * @param string $awbNumber
     * @return mixed
     * @throws InoutException
     */
    public function phoneCallHistory(string $awbNumber): mixed
    {
        return $this->get("check-actions/$awbNumber", [
            'testMode' => $this->testMode
        ]);
    }
}
