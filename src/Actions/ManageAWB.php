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
        return $this->get("get-awb/$awbNumber?testMode=$this->testMode");
    }

    /**
     * GET / Pre_AWB_INOUT_LABEL_BY_NUM_Web_Service_v1.0
     *
     * @param string $labelNum
     * @return mixed
     * @throws InoutException
     */
    public function preAwbLabelByNum(string $labelNum): mixed
    {
        return $this->get("preAWB/print/inout/label/num/$labelNum");
    }
}
