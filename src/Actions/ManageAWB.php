<?php

namespace Mchervenkov\Inout\Actions;

use Mchervenkov\Inout\Exceptions\InoutException;

trait ManageAWB
{
    /**
     * We can now offer you the ability to ask for a AWB details directly from your company’s
     * software or website by taking advantage of "AWB Details Web Service".
     *
     *  GET / AWB_Details_Web_Service_v1.0
     *
     * @param string $awbNumber
     * @return mixed
     * @throws InoutException
     */
    public function awbDetails(string $awbNumber): mixed
    {
        return $this->get("get-awb/$awbNumber", [
            'testMode' => $this->testMode
        ]);
    }

    /**
     * We can now offer you the ability to ask for a pdf file directly from your company’s
     * software or website by taking advantage of "PDF Web Service".
     *
     * GET / PDF_Web_Service_v1.2
     *
     * @param string $awbNumber
     * @param int $printFileType
     * @return mixed
     * @throws InoutException
     */
    public function awbPrint(string $awbNumber, int $printFileType = 1): mixed
    {
        return $this->get("print/$awbNumber", [
            'testMode' => $this->testMode,
            'printFileType' => $printFileType,
        ]);
    }

    /**
     * We can now offer you the ability to ask for a last shipment status directly from your company’s software or
     * website by taking advantage of "Shipment Status Web Service".
     *
     * GET / Shipment_Status_Web_Service_v1.1
     *
     * @param string $awbNumber
     * @return mixed
     * @throws InoutException
     */
    public function awbStatus(string $awbNumber): mixed
    {
        return $this->get("get-status/$awbNumber", [
            'testMode' => $this->testMode
        ]);
    }

    /**
     * We can now offer you the ability to ask for a all shipment history directly from your company’s software or
     * website by taking advantage of "Shipment History Web Service".
     *
     * GET / Shipment_History_Web_Service_v1.4
     * 
     * @param string $awbNumber
     * @param string|null $lang
     * @return mixed
     * @throws InoutException
     */
    public function awbHistory(string $awbNumber, string $lang = null): mixed
    {
        return $this->get("get-status/history/$awbNumber", [
            'testMode' => $this->testMode,
            'lang' => $lang,
        ]);
    }
}
