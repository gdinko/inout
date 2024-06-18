<?php

namespace Mchervenkov\Inout\Actions;

use Illuminate\Validation\ValidationException;
use Mchervenkov\Inout\Exceptions\InoutException;
use Mchervenkov\Inout\Exceptions\InoutValidationException;
use Mchervenkov\Inout\Hydrators\Shipment;
use Mchervenkov\Inout\Hydrators\ShipmentPrice;

trait ManageShipments
{
    /**
     * We can now offer you the ability to ask for a shipment creation directly from your company’s software or
     * website by taking advantage of "Shipment Creation".
     *
     * POST / Shipment_Creation_Web_Service_v1.5
     *
     * @param Shipment $shipment
     * @return mixed
     * @throws InoutException
     * @throws InoutValidationException
     * @throws ValidationException
     */
    public function shipmentCreation(Shipment $shipment): mixed
    {
        return $this->post(
            'createAWB',
            array_merge($shipment->validated(), ['testMode' => $this->testMode])
        );
    }

    /**
     * We can now offer you the ability to ask for a shipment price directly from your company’s software or
     * website by taking advantage of "Shipment Price Web Service".
     *
     * POST / Shipment_Price_Web_Service_v1.1
     *
     * @param ShipmentPrice $shipmentPrice
     * @return mixed
     * @throws ValidationException
     * @throws InoutException
     * @throws InoutValidationException
     */
    public function shipmentPrice(ShipmentPrice $shipmentPrice): mixed
    {
        return $this->post('shipment-price', $shipmentPrice->validated());
    }
}
