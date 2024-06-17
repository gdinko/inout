<?php

namespace Mchervenkov\Inout\Actions;

use Mchervenkov\Inout\Exceptions\InoutException;

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
     * We can now offer you the ability to ask for courier offices directly from your company’s software or
     * website by taking advantage of "Courier Offices Web Service".
     *
     * GET / Courier_Offices_Web_Service_v1.2
     *
     * @param int $courierId
     * @return mixed
     * @throws InoutException
     */
    public function getCourierOffices(int $courierId): mixed
    {
        return $this->get("offices-by-courier/$courierId");
    }
}
