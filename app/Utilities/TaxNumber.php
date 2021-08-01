<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 01 Aug 2021 00:59:16 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */

namespace Aurora\Utilities;


class TaxNumber
{

    /**
     * @var false
     */
    private bool $valid;
    private string $number;

    function __construct(
        $number = '',
        $valid = false
    ) {
        $this->valid  = $valid;
        $this->number = $number;
    }

    function setNumber($number)
    {
        $this->number = $number;
    }

    function setValid($valid)
    {
        $this->valid = $valid;
    }

    function isValid()
    {
        return $this->valid;
    }

    function getNumber()
    {
        return $this->number;
    }
}