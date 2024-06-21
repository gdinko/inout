<?php

namespace Mchervenkov\Inout\Actions;

use Illuminate\Validation\ValidationException;
use Mchervenkov\Inout\Exceptions\InoutException;
use Mchervenkov\Inout\Exceptions\InoutValidationException;
use Mchervenkov\Inout\Hydrators\WaybillsHistory;

trait ManageFulfilments
{
    /**
     * We can now offer you the ability to get the statuses history by AWB number from your company’s software or
     * website by taking advantage of "AWB History Web Service".
     *
     * POST / Waybills_History_Web_Service-v1.0
     *
     * @param WaybillsHistory $waybillsHistory
     * @return mixed
     * @throws ValidationException
     * @throws InoutException
     * @throws InoutValidationException
     */
    public function waybillsHistory(WaybillsHistory $waybillsHistory): mixed
    {
        return $this->post(
            'fulfilment/waybills-history',
            array_merge($waybillsHistory->validated(), ['testMode' => $this->getTestMode()])
        );
    }
}
