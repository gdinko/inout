<?php

namespace Mchervenkov\Inout\Actions;

use Mchervenkov\Inout\Exceptions\InoutException;

trait ManageCompanies
{
    /**
     * GET / Companies_Web_Service_v1.0
     *
     * @return mixed
     * @throws InoutException
     */
    public function getCompanies(): mixed
    {
        return $this->get('get-user-companies');
    }
}
