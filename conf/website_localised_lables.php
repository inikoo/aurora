<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 June 2017 at 02:00:59 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

function website_localised_labels($website_type) {

    $website_localised_labels = array(

        'EcomB2B' => array(


            'address_addressLine1'               => _('Address Line 1'),
            'address_addressLine2'               => _('Address Line 2'),
            'dependentLocality_neighborhood'     => _('Neighborhood'),
            'dependentLocality_district'         => _('District'),
            'dependentLocality_townland'         => _('Townland'),
            'dependentLocality_village_township' => _('Village (Township)'),
            'dependentLocality_suburb'           => _('Suburb'),
            'locality_city'                      => _('City'),
            'locality_suburb'                    => _('Suburb'),
            'locality_district'                  => _('District'),
            'locality_post_town'                 => _('Post town'),
            'administrativeArea_state'           => _('State'),
            'administrativeArea_province'        => _('Province'),
            'administrativeArea_island'          => _('Island'),
            'administrativeArea_department'      => _('Department'),
            'administrativeArea_county'          => _('County'),
            'administrativeArea_area'            => _('Area'),
            'administrativeArea_prefecture'      => _('Prefecture'),
            'administrativeArea_district'        => _('District'),
            'administrativeArea_emirate'         => _('Emirate'),
            'address_postal'                     => _('Postal code'),
            'address_country'                    => _('Country')

        )

    )


	);

	return $website_localised_labels[$website_type];

}

?>
