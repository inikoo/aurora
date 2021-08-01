<?php

/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 02 Aug 2021 04:43:24 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */

namespace Aurora\Interfaces\TaxCategory\Providers;

use Aurora\Interfaces\TaxCategory\TaxCategoryProvider;
use Aurora\Models\Utils\TaxCategory;
use PDO;

class GBR_TaxCategoryProvider implements TaxCategoryProvider
{
    private string $base_country;
    private PDO $db;
    /**
     * @var string[]
     */
    private array $taxable_countries;

    function __construct(PDO $db)
    {
        $this->db                = $db;
        $this->base_country      = 'GB';
        $this->taxable_countries = ['GB', 'IM', 'XX'];
    }

    public function getTaxCategory($invoice_address, $delivery_address, $taxNumber): TaxCategory
    {
        $tax_category = new TaxCategory($this->db);

        if (in_array($invoice_address->getCountryCode(), $this->taxable_countries) or in_array($delivery_address->getCountryCode(), $this->taxable_countries)) {
            return $tax_category->withCountryType($this->base_country, 'Standard');
        }

        return $tax_category->withCountryType($this->base_country, 'Outside');
    }
}