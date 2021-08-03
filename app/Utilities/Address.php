<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 02 Aug 2021 00:13:55 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */

namespace Aurora\Utilities;

use CommerceGuys\Addressing\Address as BaseAddress;

class Address
{

    protected BaseAddress $address;

    public function __construct(
        $countryCode = '',
        $administrativeArea = '',
        $locality = '',
        $dependentLocality = '',
        $postalCode = '',
        $sortingCode = '',
        $addressLine1 = '',
        $addressLine2 = '',
        $organization = '',
        $recipient = ''

    ) {
        $this->address = new BaseAddress();
        $this->address = $this->address->withCountryCode($countryCode)->withAdministrativeArea($administrativeArea)->withLocality($locality)->withDependentLocality($dependentLocality)->withPostalCode($postalCode)->withSortingCode($sortingCode)->withAddressLine1(
                $addressLine1
            )->withAddressLine2($addressLine2)->withGivenName($organization)->withGivenName($recipient);
    }


    public function setCountryCode($countryCode): Address
    {
        $new = clone $this;
        $new->address = $this->address->withCountryCode($countryCode);
        return $new;
    }

    public function setPostalCode($postalCode): Address
    {
        $new = clone $this;
        $new->address = $this->address->withPostalCode($postalCode);
        return $new;
    }

    public function getCountryCode(): string
    {
        return $this->address->getCountryCode();
    }

    public function getPostalCode(): string
    {
        return $this->address->getPostalCode();
    }

    public function isEuropeanUnion(): bool
    {
        return in_array($this->address->getCountryCode(), [
                                                            'AT',
                                                            'BE',
                                                            'BG',
                                                            'CY',
                                                            'CZ',
                                                            'DE',
                                                            'DK',
                                                            'EE',
                                                            'ES',
                                                            'FI',
                                                            'FR',
                                                            'GR',
                                                            'HR',
                                                            'HU',
                                                            'IE',
                                                            'IT',
                                                            'LT',
                                                            'LU',
                                                            'LV',
                                                            'MC',
                                                            'MT',
                                                            'NL',
                                                            'PL',
                                                            'PT',
                                                            'RO',
                                                            'SE',
                                                            'SI',
                                                            'SK'
                                                        ]


        );
    }
}

